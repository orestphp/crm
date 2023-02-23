<template>
    <div v-show="token" class="container">
        <nav>
            <a href="javascript:void(0)" @click.prevent="logout()">Logout</a>
        </nav>
    </div>

    <div v-show="token" class="container">
        <div class="card-content">
            <div class="notification">
                Welcome back, <strong>{{ form.email }}</strong>
                <br>
            </div>
        </div>
    </div>

    <form>
        <!-- Form Errors -->
        <div v-if="form.errors.length > 0" class="alert alert-danger">
            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
            <br>
            <ul>
                <li v-for="error in form.errors">
                    {{ error }}
                </li>
            </ul>
        </div>
        <div v-else-if="form.response" class="alert alert-success">
            <p v-if="crmUser" class="mb-0"> You successfully registered!</p>
            <p v-else-if="token || logs" class="mb-0"><strong>Success</strong> Data was saved!</p>
            <p v-else-if="!token" class="mb-0"> You successfully logged out!</p>
        </div>

        <!-- Form -->
        <div v-show="!token" class="mb-3">
            <label for="Email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="Email" aria-describedby="emailHelp" v-model="form.email">
        </div>
        <div v-show="!token" class="mb-3">
            <label for="Password" class="form-label">
                Password
                <a v-show="registerUser" href="javascript:void(0)" @click="requestToCrm(1)">You are not registered! Register at CRM</a></label>
            <input type="password" class="form-control" id="Password" v-model="form.password">
        </div>

        <div class="mb-3">
            <label for="crmUrl" class="form-label">Crm Url</label>
            <input type="text" class="form-control" id="crmUrl" aria-describedby="crmUrl" v-model="form.crmUrl">
        </div>
        <div class="mb-3">
            <label for="apiKey" class="form-label">Api Key</label>
            <input type="text" class="form-control" id="apiKey" aria-describedby="apiKey" v-model="form.apiKey">
        </div>
        <label for="apiKey" class="form-label">JSON Request to API & Response from CRM</label>
        <div class="mb-3 input-group">
            <span class="input-group-text text-bg-success">Request</span>
            <textarea class="form-control" id="request" v-model="form.request"></textarea>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text text-bg-info">Response</span>
            <textarea class="form-control" id="response" v-model="form.response"></textarea>
        </div>

        <button type="button" @click.prevent="requestToCrm()" class="btn btn-primary">Submit</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                token: localStorage.getItem('token'),
                form: {
                    email: '',
                    password: '',
                    crmUrl: `${process.env.MIX_API_URL}/api/signup/procform`,
                    apiKey: '264388973aaa9b2f9eb2aa84a9c7382e',
                    request: '',
                    response: '',
                    errors: [],
                },
                logs: {},
                registerUser: '',
                crmUser: false,
            }
        },

        mounted() {

        },

        methods: {
            requestToCrm(register = false) {
                const form = {
                    email: this.form.email,
                    password: this.form.password,
                    api_key: this.form.apiKey,
                    request: this.form.request,
                    crm_url: this.form.crmUrl,
                }
                if(register) {
                    form['register'] = 1;
                }
                let headers = {
                    'Content-Type': 'application/json',
                    'x-trackbox-username': this.form.email,
                    'x-trackbox-password': this.form.password,
                    'x-api-key': this.form.apiKey,
                }
                if(this.token) {
                    headers['Authorization'] = 'Bearer ' + this.token;
                }

                // POST request
                axios.post(this.form.crmUrl, form, {
                    headers: headers
                }).then(response => {
                    if (typeof response.data === 'object' && response.data.status === 'error') {
                        this.form.errors = _.flatten(_.toArray(response.data.data));
                    } else {
                        // Response
                        this.token = response.data.data.access_token;
                        localStorage.setItem("token", this.token);
                        // delete response.data.data.access_token;
                        //
                        this.form.response = JSON.stringify(response.data);
                        if(typeof response.data.logs === 'object') {
                            this.logs = response.data.logs;
                        }
                        if(typeof response.data.data.crmUser === 'object' && register) {
                            this.crmUser = true;
                        } else {
                            this.crmUser = false;
                        }
                        this.form.errors = [];
                        this.registerUser = '';

                        console.log(response.data.data);
                    }
                })
                .catch(error => {
                    if (typeof error.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(error.data.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                        this.registerUser = 'active';
                    }
                });
            },

            logout() {
                let headers = {
                    'Content-Type': 'application/json',
                    'x-trackbox-username': this.form.email,
                    'x-trackbox-password': this.form.password,
                    'x-api-key': this.form.apiKey,
                }
                if(this.token) {
                    headers['Authorization'] = 'Bearer ' + this.token;
                }

                // Logout frontend
                localStorage.removeItem("token");
                this.token = '';
                this.form.request = '';
                this.form.response = '';
                this.form.password = '';
                this.form.email = '';
                this.crmUser = false;

                // Logout backend
                axios.post(`${process.env.MIX_CRM_URL}/crm/logout`, {}, {
                    headers: headers
                }).then(response => {
                    if (typeof response.data === 'object' && response.data.status === 'error') {
                        this.form.errors = _.flatten(_.toArray(response.data.data));
                    } else {
                        console.log(response.data.data);
                    }
                })
                .catch(error => {
                    if (typeof error.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(error.data.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },

        }
    }
</script>

<style>
    a {
        font-weight: bold;
    }
    a.none {
        display: none;
    }
    a.active {
        display: block;
    }
</style>
