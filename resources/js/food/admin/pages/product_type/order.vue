<template>
  <div>
    <div class="d-flex">
      <router-link
        link
        route
        :to="{ name: 'Dashboard' }"
        small
        exact
        class="primary--text text-decoration-none"
        >Dashboard</router-link
      >&nbsp;/ <span class="text-muted">&nbsp;Product Category&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Order</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-content-between">
        Order Product Category List
        <v-btn
          v-if="isPermit('product-order-category-add')"
          @click="addDataModel('Add New Order Product Category')"
          class="btn_add float-right"
          small
        >
          <v-icon left>mdi-plus-circle-outline</v-icon>Add
        </v-btn>
      </v-card-title>

      <v-card-text>
        <div>
          <v-row>
            <v-col cols="4" lg="2">
              <v-select
                prepend-icon="mdi-database-eye"
                :items="tblItemNumberShow"
                dense
                v-model="paginate"
                label="Show:"
              ></v-select>
            </v-col>

            <v-col cols="8" lg="10">
              <v-text-field
                v-model="search"
                prepend-icon="mdi-clipboard-text-search"
                dense
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div class="table-responsive" v-if="!dataLoading && allData.data">
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head table_head_link">
                  <th>Order Product Category (English)</th>
                  <th>Order Product Category (Bangla)</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>{{ singleData.name_en }}</td>
                  <td>{{ singleData.name_bn }}</td>

                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="
                        isPermit('product-order-category-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                      :style="
                        isPermit('product-order-category-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-order-category-edit')"
                      @click="
                        editDataModel(
                          singleData,
                          'Update Order Product Category'
                        )
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-order-category-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>

                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">
                        {{ singleData.makby.name }}
                      </span>
                      <span v-else class="error--text">N/A</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <v-pagination
              circle
              v-model="currentPageNumber"
              :length="v_total"
              :total-visible="7"
              @input="getResults"
            ></v-pagination>
            <div>
              <span>Total Records: {{ totalValue }}</span>
            </div>
          </div>

          <div v-else>
            <div v-if="dataLoading">
              <tbl-data-loader />
            </div>
          </div>
        </div>

        <data-not-available v-if="!totalValue && !dataLoading" />
      </v-card-text>
    </v-card>

    <v-dialog max-width="900" v-model="dataModal">
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">{{ dataModelTitle }}</v-col>
            <v-col cols="2">
              <v-btn
                @click="dataModal = false"
                small
                class="float-right btn_close"
              >
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <form @submit.prevent="editmode ? updateData() : createData()">
            <v-text-field
              v-model="form.name_en"
              label="Order Product Category Name (English)"
              placeholder="Enter Order Product Category Name (English)"
              :error-messages="form.errors.get('name_en')"
            ></v-text-field>

            <v-text-field
              v-model="form.name_bn"
              label="Order Product Category Name (Bangla)"
              placeholder="Enter Order Product Category Name (Bangla)"
              :error-messages="form.errors.get('name_bn')"
            ></v-text-field>

            <v-btn
              v-show="editmode"
              type="submit"
              block
              class="my-2 btn_update"
              :loading="dataLodaing"
            >
              <v-icon left>mdi-circle-edit-outline</v-icon>Update
            </v-btn>
            <v-btn
              v-show="!editmode"
              type="submit"
              block
              class="my-2 btn_save"
              :loading="dataLodaing"
            >
              <v-icon left>mdi-plus-circle-outline</v-icon>Create
            </v-btn>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
// vform
import Form from "vform";
export default {
  data() {
    return {
      //current page url
      currentUrl: "/admin/order_type",

      imagePathSm: "/images/admin/small/",
      imagePath: "/images/admin/",
      imageMaxSize: "2111775",

      // Form
      form: new Form({
        id: "",
        name_en: "",
        name_bn: "",
      }),
    };
  },

  mounted() {
    this.$Progress.start();
    // Fetch initial results
    this.getResults();
    this.$Progress.finish();
  },
};
</script>

