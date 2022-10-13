import CHECKAUTH from '../../../../auth';

export default {

    // Get table data
    getResults(page = 1) {
        this.dataLoading = true;
        return axios.get(this.currentUrl + '/index?page=' + page +
            '&paginate=' + this.paginate +
            '&search=' + this.search +
            '&sort_direction=' + this.sort_direction +
            '&sort_field=' + this.sort_field +
            '&search_field=' + this.search_field +
            "&start_date=" + this.start_date +
            "&end_date=" + this.end_date +
            "&by_days=" + this.by_days +
            "&by_zone=" + this.by_zone +
            "&by_type=" + this.by_type +
            "&sort_direction_custom=" + this.sort_direction_custom +
            "&sort_field_custom=" + this.sort_field_custom
        )
            .then(response => {
                console.log("test", response.status);
                //console.log(response.data.from, response.data.to, response.data.current_page);
                this.allData = response.data;
                this.totalValue = response.data.total;
                this.dataShowFrom = response.data.from;
                this.dataShowTo = response.data.to;
                this.currentPageNumber = response.data.current_page
                this.v_total = response.data.last_page
                this.totalOnline = response.data.total_online



                // Loading Animation
                this.dataLoading = false;

            }).catch(error => {
                CHECKAUTH.CheckByCode(error.response.status)
                console.log('error', error.response.status,  error)
            });
    },


    // DataTable Shorting by field name
    change_sort(field) {
        this.$Progress.start();
        if (this.sort_field == field) {
            this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc";
        } else {
            this.sort_field = field;
        }
        this.getResults();
        this.$Progress.finish();
    },

    // DataTable Shorting by Custom field name
    change_sort_custom(field) {
        this.$Progress.start();
        if (this.sort_field_custom == field) {
            this.sort_direction_custom = this.sort_direction_custom == "asc" ? "desc" : "asc";
        } else {
            this.sort_field_custom = field;
        }
        this.getResults();
        this.$Progress.finish();
    },


}
