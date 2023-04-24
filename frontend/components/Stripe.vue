<template>
  <div class="payment">
    <label>card Number</label>
    <div class="payment-form" ref="cardElement"></div>
    <button @click="submitPayment" class="payment-btn btn">決済</button>
  </div>
</template>
<script>
export default {
  props: {
    reservation: {},
  },
  methods: {
    async submitPayment() {
      const { paymentMethod } = await this.$stripe.createPaymentMethod({
        type: 'card',
        card: this.cardElement,
        billing_details: {
          name: this.$store.state.user.name,
          email: this.$store.state.user.email
        }
      })
      if (!paymentMethod) {
        alert('決済に失敗しました')
        return
      }
      if (this.reservation.price === 0) {
        alert('席のみの予約のため店頭でお支払い下さい')
        return
      }
      const response = await this.$axios.post("/api/payment/", {
        payment_method_id: paymentMethod.id,
        amount: this.reservation.price,
      }
      )
        console.log(response.data)
        if (response.data.success === true) {
          alert('決済が完了しました')
        } else {
          console.log(response.data.message)
          alert('決済に失敗しました')
        }
      }
    },
  async mounted() {
    const elements = this.$stripe.elements()
    this.cardElement = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: 'white',
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: 'antialiased',
          '::placeholder': {
            color: '#aab7c4'
          }
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a'
        }
      }
    })
    this.cardElement.mount(this.$refs.cardElement)
  }
}
</script>
<style scoped>
.payment {
  margin-top: 25px;
}
.payment-form {
  margin-top: 10px;
}
.payment-btn {
  margin: 20px 0 0 70%;
  background-color: rgb(72, 145, 254);
  border-radius: 5px;
  border: none;
  color: white;
  cursor: pointer;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  height: 30px;
}
</style>