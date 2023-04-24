<template>
  <div class="qrcode flex">
    <p class="ttl">予約時はこのQRコードを表示し店舗窓口へ</p>
    <vue-qrcode class="qrcode-img" v-if="targetText" :value="targetText" :options="option" tag="img"><div>{{ JSON.stringify(reservation) }}</div></vue-qrcode>
    <button class="btn qrcode-btn" @click="$router.back()">マイページへ戻る</button>
  </div>
</template>

<script>
import VueQrcode from "@chenfengyuan/vue-qrcode";
export default {
  components: {
    VueQrcode
  },
  data() {
    return {
      reservation: {
        name: 'are',
        time: 'sore'
      },
      targetText: "",
      option: {
        errorCorrectionLevel: "M",
        maskPattern: 0,
        margin: 10,
        scale: 2,
        width: 300,
        color: {
          dark: "#000000FF",
          light: "#FFFFFFFF"
        }
      }
    };
  },
  methods: {
    generate () {
      this.targetText = JSON.stringify('予約番号：' + this.$route.params.id+ 'で承っております')
    },
  },
  async created() {
    await this.generate()
  }
};
</script>
<style scoped>
.qrcode {
  justify-content: center;
  width: 100%;
}
.ttl {
  width: 100%;
  text-align: center;
  font-size: large;
  font-weight: bold;
}
.qrcode-img {
  width: 500px;
  margin: 40px calc(100% - 500px);
}
.qrcode-btn {
  height: 30px;
  width: 200px;
  font-size: large;
}
</style>