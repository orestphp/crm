<template>
    <form>
        <div class="mb-3">
            <label for="Email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="Email" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="Password" class="form-label">Password</label>
            <input type="password" class="form-control" id="Password">
        </div>
        <div class="mb-3">
            <label for="Password repeat" class="form-label">Password repeat</label>
            <input type="password" class="form-control" id="Password repeat">
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text">JSON Request</span>
            <textarea class="form-control"></textarea>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text">Response</span>
            <textarea class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
