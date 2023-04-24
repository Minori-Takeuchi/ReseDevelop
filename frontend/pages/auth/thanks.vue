<template>
  <div class="container thanks">
    <div class="flex jc-center thanks-wrap">
      <h1 class="thanks-ttl">会員登録ありがとうございます</h1>
      <button @click="$router.push('/auth/login')" class="thanks-btn btn">ログインする</button>
    </div>
  </div>
</template>
<style scoped>
.thanks {
  display: flex;
  justify-content: center;
}
.thanks-wrap {
  width: 40%;
  background-color: white;
  border-radius: 5px;
  margin-top: 80px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}
.thanks-ttl {
  width: 100%;
  text-align: center;
  margin: 80px 0;
  font-size: larger;
}
.thanks-btn {
  margin-bottom: 50px;
  width: 150px;
  height: 40px;
  font-size: large;
}

@media screen and (max-width:768px) {
  .thanks-wrap {
    width: 80%;
  }
}

</style>