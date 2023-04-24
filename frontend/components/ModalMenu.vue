<template>
  <div @click="closeModal" class="overlay">
    <div class="window">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    closeModal() {
      this.$emit('closeModal')
    }
  }
}
</script>

<style scoped>
.modal {
  width: 100vw;
  height: 100vh;
}
.overlay{
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  z-index: 100;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background: rgba(0, 0, 0, 0.7);
}
.window {
  height: 70%;
  width: 70%;
  overflow-y: auto;
  background-color: rgb(232, 234, 238);
  border-radius: 5px;
  padding: 40px;
}
</style>