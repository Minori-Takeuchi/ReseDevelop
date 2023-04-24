<template>
  <div class="container done">
    <div class="flex jc-center  done-wrap">
      <h1 class="done-ttl">ご予約ありがとうございます</h1>
      <button @click="goMypage" class="done-btn btn">戻る</button>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    goMypage() {
      this.$router.push({ path: `/mypage/${this.$store.state.user.uid}` });
    }
  }
}
</script>
<style scoped>
.done {
  display: flex;
  justify-content: center;
}
.done-wrap {
  width: 40%;
  background-color: white;
  border-radius: 5px;
  margin-top: 80px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}
.done-ttl {
  width: 100%;
  text-align: center;
  margin: 80px 0;
  font-size: larger;
}
.done-btn {
  margin-bottom: 50px;
  width: 15%;
  height: 40px;
  font-size: large;
}

@media screen and (max-width:768px) {
  .done-wrap {
    width: 80%;
  }
}
</style>