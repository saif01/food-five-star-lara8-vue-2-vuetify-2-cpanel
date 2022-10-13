export default {

    // Create Data
    createData() {
        this.dataLodaing = true;
        this.$Progress.start()
        // request send and get response
        this.form.post(this.currentUrl + '/store' + '').then(response => {
            // Input field make empty
            this.resetForm();
            
            // modal hide
            this.dataModal = false;
            this.dataLodaing = false;

            // Refresh Tbl Data with current page
            this.getResults(this.currentPageNumber);
            this.$Progress.finish();

            Toast.fire({
                icon: response.data.icon,
                title: response.data.msg
            });

        }).catch(error=>{
            //this.dataModal = false;
            this.dataLodaing = false;
            Swal.fire("Failed!", data.message, "warning");
        });

    },


    // Update data
    updateData() {
        this.dataLodaing = true;
        this.$Progress.start()
        // request send and get response
        this.form.put(this.currentUrl + '/update/' + this.form.id).then(response => {
            // Input field make empty
            this.resetForm();
            
            // modal hide
            this.dataModal = false;
            this.dataLodaing = false;

            // Refresh Tbl Data with current page
            this.getResults(this.currentPageNumber);
            this.$Progress.finish();

            Toast.fire({
                icon: response.data.icon,
                title: response.data.msg
            });

        }).catch(error=>{
            this.dataModal = false;
            this.dataLodaing = false;
            Swal.fire("Failed!", data.message, "warning");
        });

    },
    

    // Delete Data
    deleteData(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            // Send request to the server
            if (result.value) {
                //console.log(id);
                this.$Progress.start();
                this.form.delete(this.currentUrl + '/destroy/' + id).then((response) => {
                    //console.log(response);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                    // Refresh Tbl Data with current page
                    this.getResults(this.currentPageNumber);
                    this.$Progress.finish();

                }).catch((data) => {
                    Swal.fire("Failed!", data.message, "warning");
                });
            }
        })
    },

    // Change Status
    statusChange(data) {

        if (data.status == 1) {
            var text = "Are you want to inactive ?"
            var btnText = "Inactive"

        } else {
            var text = "Are you want to active ?"
            var btnText = "Active"
        }

        Swal.fire({
            title: 'Are you sure?',
            text: text,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: btnText,
        }).then((result) => {

            // Send request to the server
            if (result.value) {
                //console.log(id);
                this.$Progress.start();
                axios.post(this.currentUrl + '/status/' + data.id).then((response) => {
                    //console.log(response);
                    Swal.fire(
                        'Changed!',
                        'Status has been Changed.',
                        'success'
                    );
                    // Refresh Tbl Data with current page
                    this.getResults(this.currentPageNumber);
                    this.$Progress.finish();

                }).catch((data) => {
                    Swal.fire("Failed!", data.message, "warning");
                });
            }
        })
    },

    // Add Data Model
    addDataModel() {
        this.dataModal = true;
        this.editmode = false;
        this.resetForm();
    },

    // Edit Data Modal
    editDataModel(singleData) {
        this.dataModal = true;
        this.editmode = true;
        this.dataModelTitle = 'Update Data'
        this.resetForm();
        this.form.fill(singleData);
        
    },

    // reset form
    resetForm(){
        this.form.reset();
        // this.$refs.form.resetValidation();
        this.form.errors.clear();
    }



}
