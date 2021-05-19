<template>
  <div class="flex h-screen">
    <div class="mx-auto my-auto">Payload test: {{ payload }}</div>
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
    Echo.channel('test').listen('Test', (e) => {
      this.payload = e
    })
  },
}
</script>
