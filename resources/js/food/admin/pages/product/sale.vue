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
      >Dashboard</router-link>&nbsp;/
      <span class="text-muted">&nbsp;Product&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Sale</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Sale Product List</v-col>

          <v-col cols="2">
            <v-btn
              v-if="isPermit('product-sale-add')"
              @click="addDataModel('Add New Saleable Product')"
              class="btn_add float-right"
              small
            >
              <v-icon left>mdi-plus-circle-outline</v-icon>Add
            </v-btn>
          </v-col>
        </v-row>
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

          <div class="table-responsive" v-if="!dataLoading && allData.data.length">
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head table_head_link">
                  <th>
                    <a href="#" @click.prevent="change_sort('image')">Image</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'image'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'image'">&darr;</span>
                  </th>
                  <th>Category</th>
                  <th>
                    <a href="#" @click.prevent="change_sort('name_en')">Product (English)</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'name_en'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'name_en'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('name_bn')">Product (Bangla)</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'name_bn'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'name_bn'">&darr;</span>
                  </th>
                  <!-- <th>Under Orderable Product</th> -->
                  <th>
                    <a href="#" @click.prevent="change_sort('price')">Item Price</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'price'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'price'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('price_offer')">Offer Price</a>
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'price_offer'
                      "
                    >&uarr;</span>
                    <span
                      v-if="
                        sort_direction == 'asc' && sort_field == 'price_offer'
                      "
                    >&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('quantity_per_set')">Qty in Per Set</a>
                    <span
                      v-if="
                        sort_direction == 'desc' &&
                        sort_field == 'quantity_per_set'
                      "
                    >&uarr;</span>
                    <span
                      v-if="
                        sort_direction == 'asc' &&
                        sort_field == 'quantity_per_set'
                      "
                    >&darr;</span>
                  </th>

                  <th>
                    <a href="#" @click.prevent="change_sort('free_item')">Free Item</a>
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'free_item'
                      "
                    >&uarr;</span>
                    <span
                      v-if="
                        sort_direction == 'asc' && sort_field == 'free_item'
                      "
                    >&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('status')">Action</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'status'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'status'">&darr;</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td class="text-center">
                    <span v-if="singleData.image">
                      <img
                        :src="imagePathSm + singleData.image"
                        alt="Image"
                        height="100"
                        width="100"
                      />
                    </span>
                    <span v-else class="text-danger">No Image</span>
                  </td>
                  <td>
                    <span v-if="singleData.type">
                      {{
                      singleData.type.name_en
                      }}
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>
                  <td>{{ singleData.name_en }}</td>
                  <td>{{ singleData.name_bn }}</td>
                  <!-- <td>
                    <span v-if="singleData.sales_product">
                      {{ singleData.sales_product.type }} (
                      {{ singleData.sales_product.product_code }} )
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>-->
                  <td>{{ singleData.price }}</td>
                  <td>
                    <span v-if="singleData.price_offer">
                      {{
                      singleData.price_offer
                      }}
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td>
                    <span v-if="singleData.quantity_per_set">{{ singleData.quantity_per_set }} Pcs</span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td>
                    <span v-if="singleData.free">
                      {{
                      singleData.free.name_en
                      }}
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>
                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="(isPermit('product-sale-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                      :style="(isPermit('product-sale-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-sale-edit')"
                      @click="
                        editDataModel(singleData, 'Update Saleable Product'),
                          (form.free_item = parseInt(singleData.free_item))
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-sale-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>

                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">
                        {{
                        singleData.makby.name
                        }}
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

    <v-dialog v-model="dataModal">
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">{{ dataModelTitle }}</v-col>
            <v-col cols="2">
              <v-btn @click="dataModal = false" small class="float-right btn_close">
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <form @submit.prevent="editmode ? updateData() : createData()">
            <v-row>
              <v-col cols="12" lg="4">
                <v-autocomplete
                  v-model="form.type_id"
                  label="Sales Product Category (if any)"
                  placeholder="Select Sales Product Category (if any)"
                  :items="typesData"
                  item-text="name_en"
                  item-value="id"
                  :error-messages="form.errors.get('type_id')"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.name_en"
                  label="Sales Product Name (English)"
                  placeholder="Enter Sales Product Name (English)"
                  :error-messages="form.errors.get('name_en')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.name_bn"
                  label="Sales Product Name (Bangla)"
                  placeholder="Enter Sales Product Name (Bangla)"
                  :error-messages="form.errors.get('name_bn')"
                ></v-text-field>
              </v-col>
              <!-- <v-col cols="12" lg="4">
                <v-autocomplete
                  v-model="form.product_order_id"
                  label="Under Orderable Product"
                  placeholder="Select Under Orderable Product"
                  :items="saleProductsList"
                  item-text="type"
                  item-value="id"
                  :error-messages="form.errors.get('product_order_id')"
                ></v-autocomplete>
              </v-col>-->
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.price"
                  type="number"
                  label="Sales Product Price"
                  placeholder="Enter Sales Product Price"
                  :error-messages="form.errors.get('price')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.price_offer"
                  type="number"
                  label="Sales Product Offer Price"
                  placeholder="Enter Sales Product Offer Price"
                  :error-messages="form.errors.get('price_offer')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.quantity_per_set"
                  type="number"
                  label="Sales Product Quantity in Per Set"
                  placeholder="Enter Sales Product Quantity in Per Set"
                  :error-messages="form.errors.get('quantity_per_set')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-autocomplete
                  v-model="form.free_item"
                  label="Free Sales Product"
                  placeholder="Select Free Sales Product"
                  :items="freeItem"
                  item-text="name_en"
                  item-value="id"
                  clearable
                  :error-messages="form.errors.get('free_item')"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" lg="4">
                <v-file-input
                  @change="upload_image($event)"
                  label="Image"
                  placeholder="Select or drop Image here..."
                  class="is-invalid"
                  accept=".jpg, .png, .jpeg"
                  :error-messages="form.errors.get('image')"
                  persistent-hint
                  hint="Dimension must be 400*400"
                ></v-file-input>
              </v-col>
              <v-col cols="12" lg="4">
                <div class="mt-1">
                  <img :src="get_image_product()" height="100" width="100" />
                </div>
              </v-col>
            </v-row>

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
      currentUrl: "/admin/product",

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
      imageMaxSize: "2111775",

      // Form
      form: new Form({
        id: "",
        name_en: "",
        name_bn: "",
        product_order_id: "",
        image: "",
        type: "",
        price: "",
        price_offer: "",
        quantity_per_set: "",
        free_item: "",
        type_id: 4
      }),

      // typesData
      typesData: [],
      // saleProductsList
      saleProductsList: [],

      freeItem: []
    };
  },

  methods: {
    getType() {
      return axios.get(this.currentUrl + "/type").then(response => {
        this.typesData = response.data;
      });
    },

    getSaleProducts() {
      return axios.get(this.currentUrl + "/sales_product").then(response => {
        this.saleProductsList = response.data;
      });
    },

    getFreeItems() {
      return axios.get(this.currentUrl + "/free_item").then(response => {
        this.freeItem = response.data;
      });
    }
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults();
    await this.getFreeItems();
    await this.getType();
    await this.getSaleProducts();
    this.$Progress.finish();
  }
};
</script>

