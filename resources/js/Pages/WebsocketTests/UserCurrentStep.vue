<template>
    <div class="flex h-screen">
        <div class="mx-auto my-auto">Session status: {{ payload }}</div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            payload: {},
        }
    },
    mounted() {
        this.payload = 'empty'
        Echo.private(`user.${this.$inertia.page.props.user.id}`).listen(
            `.current.session`,
            (e) => {
                this.payload = e.session.status
                console.log(e)
            }
        )
    },
}
</script>
