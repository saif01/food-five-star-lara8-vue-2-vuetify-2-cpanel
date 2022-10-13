export default {
    getModifyOrder(data, index = null) {
        this.modifyOverlay = true;
        this.purchaseItem = [];
        axios
            .post("/modify/order_items", {
                sales_number: data.sales_numb,
            })
            .then((response) => {
                response.data.product.forEach((product) => {
                    response.data.sale.forEach((sale) => {
                        if (product.id === sale.product_id) {
                            product.quantity = sale.quantity;
                        }
                    });
                });

                this.purchaseItem = response.data.product;
                this.$store.commit("setCart", this.purchaseItem);
                this.$store.commit("setCdetails", response.data.sale[0].summury);

                if (index == null) {
                    this.$router.push({
                        name: "Dashboard",
                        params: {
                            from: "modify",
                        },
                    });
                }

                this.modifyOverlay = false;
            });
    },


    // getOrderDetails
    getOrderDetails(data) {
        this.modifyOverlay = true;
        axios
            .post("/modify/order_details", {
                sales_number: data.sales_numb,
            })
            .then((resoponse) => {
                this.currentOrderDetails = resoponse.data;
                this.modifyOverlay = false;
                this.currentOrderDetailsDialog = true;
            });
    },



    quickEditSale() {
        this.quickDataLoading = true;
        axios.get("/modify/quick_search").then((response) => {
            this.quickSearchData = response.data;
            this.quickDataLoading = false;
        });
    },
}