export default {

    // Upload Image
    upload_image (e) {
        let file = e;
        let reader = new FileReader();

        if (file['size'] < this.imageMaxSize) {
            reader.onloadend = (file) => {
                //console.log('RESULT', reader.result)
                this.form.image = reader.result;
            }
            reader.readAsDataURL(file);
        } else {
            alert('File size can not be bigger than 2 MB')
        }
        
    },

    //For getting Instant Uploaded Photo
    get_image() {

        if (this.form.image) {
            let photo = (this.form.image.length > 100) ? this.form.image : this.imagePathSm + this.form.image;
            return photo;
        }
        return "/all-assets/common/img/no-image.png";
    },


    // File Upload
    onFileChange(e){
        let file = e.target.files[0];

        if(file['size'] < this.fileMaxSize) {
            this.form.document = file;
        }else{
             alert('File size can not be bigger than 5 MB')
        }
    },

    // image Upload by name
    uploadImageByName: function (event, currentFieldName) {

        let file = event;
        let reader = new FileReader();
        console.log('Image upload: ',file, file['size'] )
        if (file['size'] < this.imageMaxSize) {
            reader.onloadend = (file) => {
                console.log('RESULT', reader.result)
                this.form[currentFieldName] = reader.result;
            }
            reader.readAsDataURL(file);
        } else {
            alert('File size can not be bigger than 2 MB')
        }


    },

    // Selected Image Show
    showImageByName() {

        if (this.form.image) {
            let photo = (this.form.image.length > 100) ? this.form.image : this
                .imagePathSm + this.form.image;
            return photo;
        }
        return "/all-assets/common/img/no-image.png";
    },
    
}