export default {
  // placeOrder
  placeOrder() {
    this.orderLoading = true;
    axios
      .post(this.currentUrl + "/store", {
        items: this.orderList,
        order_date: this.selected_date,
      })
      .then((response) => {
        this.orderLoading = false;
        // Swal.fire("Changed!", "Order has been Placed.", "success");

        if (this.currentLang == 'bangla') {
          Swal.fire({
            title: response.data.title_bn, text: response.data.msg_bn, icon: response.data.icon, showConfirmButton: false
          });
        } else {
          Swal.fire({
            title: response.data.title, text: response.data.msg, icon: response.data.icon, showConfirmButton: false
          });
        }


        //push to all order
        this.$router.push({ name: "AllOrder" });

        // Refresh Tbl Data with current page

        this.getFoodItemForOrder();
        (this.selected_date = this.$moment().format("YYYY-MM-DD")),
          this.$Progress.finish();
        this.orderList = [];
        this.placeOrderDialog = false;

      })
      .catch((error) => {
        this.orderLoading = false;
        // Swal.fire("Failed!", data.message, "warning");

        //Auth Check
        this.$CHECKAUTH.CheckByCode(error.response.status);
        console.log("error", error.response.status, error);

        // if (this.currentLang == 'bangla') {
        //   Swal.fire('ফেইল্ড', response.error.msg_bn, 'warning');
        // } else {
        //   Swal.fire('Failed!', response.error.msg, 'warning');
        // }
      });
  },

  // modifyOrder
  placeOrderModify() {
    this.orderLoading = true;
    axios
      .post(this.currentUrl + "/update", {
        items: this.orderList,
        order_date: this.selected_date,
        order_number: this.order_number,
      })
      .then((response) => {
        this.orderLoading = false;
        //console.log(response);
        // Swal.fire("Changed!", "Order has been Updated.", "success");
        if (this.currentLang == 'bangla') {
          Swal.fire({
            title: response.data.title_bn, text: response.data.msg_bn, icon: response.data.icon, showConfirmButton: false
          });
        } else {
          Swal.fire({
            title: response.data.title, text: response.data.msg, icon: response.data.icon, showConfirmButton: false
          });
        }
        // Refresh Tbl Data with current page
        //this.getFoodItemForOrder();
        this.$Progress.finish();
        this.orderList = [];
        this.$emit("getTriggered");
        this.placeOrderDialog = false;
        this.modifyModal = false;
      })
      .catch((data) => {
        this.orderLoading = false;
        //Auth Check
        this.$CHECKAUTH.CheckByCode(error.response.status);
        console.log("error", error.response.status, error);
        // if (this.currentLang == 'bangla') {
        //   Swal.fire('ফেইল্ড', response.data.msg_bn, 'warning');
        // } else {
        //   Swal.fire('Failed!', response.data.msg, 'warning');
        // }
      });
  },

  // addToOrder
  addToOrder(item) {
    const count = this.orderList.filter((obj) => obj.id == item.id).length;
    // check double item
    if (count > 0) {
      // remove double item
      const removeIndex = this.orderList.findIndex(
        (obj) => obj.id === item.id
      );
      item.quantity = 1;
      this.orderList.splice(removeIndex, 1);

      this.snackbar = true;
      if (this.currentLang == 'bangla') {
        this.snackbarText = "সফলভাবে মুছে ফেলা হয়েছে";
      } else {
        this.snackbarText = "Deleted Successfully";
      }
    } else {
      this.orderList.push(item);

      this.snackbar = true;
      if (this.currentLang == 'bangla') {
        this.snackbarText = "সফলভাবে যোগ করা হয়েছে";
      } else {
        this.snackbarText = "Added Successfully";
      }
    }

    // this.$store.commit("setOrder", this.orderList);
  },


  // find id to show remove item button
  findId(item) {
    const count = this.orderList.filter((obj) => obj.id == item.id).length;
    // check double item
    if (count > 0) {
      return true;
    } else {
      return false;
    }
  },

  // modify order list using modify list button
  modifyOrder(item) {
    const removeIndex = this.orderList.findIndex((obj) => obj.id === item.id);
    this.orderList.splice(removeIndex, 1);

    this.orderList.push(item);
    // this.$store.commit("setOrder", this.orderList);

    this.snackbar = true;
    this.snackbarText = "Order Modified";
  },

  // ModifyQuantityOrder
  ModifyQuantityOrder(item) {
    const orderListItemMatched = this.orderList.filter(
      (obj) => obj.id == item.id
    );
    // check double item
    if (orderListItemMatched.length > 0) {
      // remove double item
      orderListItemMatched[0].quantity_order = item.quantity_order;
    } else {

      this.orderList.push(item);
    }
  },


  checkQuantity(data) {
    data.quantity_order = Math.round(parseInt(data.quantity_order));
    // this.ModifyQuantityOrder(data);
    if (data.quantity_order == 0 || Number.isNaN(data.quantity_order)) {
      if (this.currentLang == "bangla") {
        Swal.fire({
          text: "আপনি কি এই আইটেম সিরিয়ে ফেলতে চান?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "হ্যাঁ, সরিয়ে ফেলুন!",
          allowOutsideClick: false,
        });
      } else {
        Swal.fire({
          text: "Do you want to remove this item?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, remove it!",
          allowOutsideClick: false,
        }).then((result) => {
          if (result.isConfirmed) {

            if (this.findId(data)) {
              this.addToOrder(data);
            }

            data.quantity_order = 1;


          }
          if (result.isDismissed) {
            data.quantity_order = 1;
            //this.addToOrder(data);
          }

        });
      }
    } else {
      this.ModifyQuantityOrder(data);
    }
  },
}