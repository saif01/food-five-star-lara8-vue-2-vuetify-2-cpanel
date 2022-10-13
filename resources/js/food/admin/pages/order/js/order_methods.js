export default {

  // modifyOrder
  modifyOrder() {
    this.orderLoading = true;
    axios
      .post(this.currentUrl + "/update", {
        items: this.orderList,
        order_date: this.selected_date,
        order_number: this.order_number,
        cv_code: this.selected_cv_code
      })
      .then(response => {
        this.orderLoading = false;
        // Swal.fire("Changed!", "Order has been Modified.", "success");
        Swal.fire(
          response.data.icon,
          response.data.msg,
          response.data.icon
        );
        // emititng for load index page
        this.$emit("getTriggered");
        this.$Progress.finish();
        this.orderList = [];
        //this.$store.commit("setOrder", []);
        this.placeOrderDialog = false;
        this.modifyModal = false;
      })
      .catch(data => {
        this.orderLoading = false;
        //Auth Check
        this.$CHECKAUTH.CheckByCode(error.response.status);
        console.log("error", error.response.status, error);
      });
  },

  // addToOrder
  addToOrder(item) {
    const count = this.orderList.filter(obj => obj.id == item.id).length;
    // check double item
    if (count > 0) {
      // remove double item
      const removeIndex = this.orderList.findIndex(obj => obj.id === item.id);
      item.quantity_order = 1;
      this.orderList.splice(removeIndex, 1);

      this.snackbar = true;
      this.snackbarText = "Deleted Successfully";
    } else {
      this.orderList.push(item);
      this.snackbar = true;
      this.snackbarText = "Added Successfully";
    }

    // this.$store.commit("setOrder", this.orderList);
  },

  // find id to show remove item button
  findId(item) {
    const count = this.orderList.filter(obj => obj.id == item.id).length;
    // check double item
    if (count > 0) {
      return true;
    } else {
      return false;
    }
  },

  // ModifyQuantityOrder
  ModifyQuantityOrder(item) {
    const orderListItemMatched = this.orderList.filter(
      obj => obj.id == item.id
    );
    //console.log('orderListItemMatched', count,  orderListItemMatched[0].quantity, orderListItemMatched.length)

    // check double item
    if (orderListItemMatched.length > 0) {
      // remove double item
      orderListItemMatched[0].quantity_order = item.quantity_order;

      // this.snackbar = true;
      // this.snackbarText = "Deleted Successfully";
    } else {
      this.orderList.push(item);
      // this.snackbar = true;
      // this.snackbarText = "Added Successfully";
    }
  },



  // placeOrder
  placeOrder() {
    if (!this.selected_cv_code) {
      Swal.fire(
        "Warning!",
        "Shop Name Required for Complete Order",
        "warning"
      );
    } else {
      this.orderLoading = true;
      axios
        .post(this.currentUrl + "/store", {
          items: this.orderList,
          cv_code: this.selected_cv_code,
          order_date: this.selected_date,
        })
        .then((response) => {
          this.orderLoading = false;
          Swal.fire(
            response.data.icon,
            response.data.msg,
            response.data.icon
          );
          // emititng for load index page
          this.$emit("getTriggered");
          this.$Progress.finish();
          this.orderList = [];
          //this.$store.commit("setOrder", []);
          this.orderModal = false;
          this.placeOrderDialog = false;
        })
        .catch((error) => {
          this.orderLoading = false;
          //Auth Check
          this.$CHECKAUTH.CheckByCode(error.response.status);
          console.log("error", error.response.status, error);
        });
    }
  },



  // checkQuantity
  checkQuantity(data) {
    data.quantity_order = Math.round(parseInt(data.quantity_order));

    if (data.quantity_order == 0 || Number.isNaN(data.quantity_order)) {
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
          // this.addToOrder(data);
          if (this.findId(data)) {
            this.addToOrder(data);
          }
          data.quantity_order = 1;

        }
        if (result.isDismissed) {
          data.quantity_order = 1;

        }
      });
    } else {
      this.ModifyQuantityOrder(data);
    }
  },




  // ModifyQuantityOrder
  ModifyAdjustQuantityOrder(item) {
    const orderListItemMatched = this.orderList.filter(
      obj => obj.order_number == item.order_number
    );
    //console.log('orderListItemMatched', count,  orderListItemMatched[0].quantity, orderListItemMatched.length)

    // check double item
    if (orderListItemMatched.length > 0) {
      // remove double item
      orderListItemMatched[0].quantity_order = item.quantity_order;

      // this.snackbar = true;
      // this.snackbarText = "Deleted Successfully";
    } else {
      this.orderList.push(item);
      // this.snackbar = true;
      // this.snackbarText = "Added Successfully";
    }
  },


}