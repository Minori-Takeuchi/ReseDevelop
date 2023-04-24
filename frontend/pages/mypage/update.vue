<template>
  <div class="container update">
    <div class="flex jc-center  update-wrap">
      <h1 class="update-ttl">予約の変更を受付ました</h1>
      <button @click="goMypage" class="update-btn btn">戻る</button>
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
.update {
  display: flex;
  justify-content: center;
}

.update-wrap {
  width: 40%;
  background-color: white;
  border-radius: 5px;
  margin-top: 80px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}

.update-ttl {
  width: 100%;
  text-align: center;
  margin: 80px 0;
  font-size: larger;
}

.update-btn {
  margin-bottom: 50px;
  width: 15%;
  height: 40px;
  font-size: large;
}

@media screen and (max-width:768px) {
  .update-wrap {
    width: 80%;
  }
}

</style>