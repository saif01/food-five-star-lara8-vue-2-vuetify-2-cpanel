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
      <span class="font-weight-bold">&nbsp;Order</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Order Product List</v-col>

          <v-col cols="2">
            <v-btn
              v-if="isPermit('product-order-add')"
              @click="addDataModel('Add New Orderable Product')"
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
                    <a href="#" @click.prevent="change_sort('product_code')">Product Code</a>
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'product_code'
                      "
                    >&uarr;</span>
                    <span
                      v-if="
                        sort_direction == 'asc' && sort_field == 'product_code'
                      "
                    >&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('image')">Image</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'image'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'image'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('type')">Products (English)</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'type'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'type'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('type_bn')">Products (Bangla)</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'type_bn'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'type_bn'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('category')">Category</a>
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'category'
                      "
                    >&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'category'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('packaging_type')">Packaging Type</a>
                    <span
                      v-if="
                        sort_direction == 'desc' &&
                        sort_field == 'packaging_type'
                      "
                    >&uarr;</span>
                    <span
                      v-if="
                        sort_direction == 'asc' &&
                        sort_field == 'packaging_type'
                      "
                    >&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('weight')">Weight</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'weight'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'weight'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('quantity')">Quantity</a>
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'quantity'
                      "
                    >&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'quantity'">&darr;</span>
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('tp')">TP (Inc. VAT)</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'tp'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'tp'">&darr;</span>
                  </th>
                  <!-- <th>
                    <a href="#" @click.prevent="change_sort('mrp')"
                      >MRP (Inc. VAT)</a
                    >
                    <span v-if="sort_direction == 'desc' && sort_field == 'mrp'"
                      >&uarr;</span
                    >
                    <span v-if="sort_direction == 'asc' && sort_field == 'mrp'"
                      >&darr;</span
                    >
                  </th>-->
                  <!-- <th>
                    <a href="#" @click.prevent="change_sort('remark')"
                      >Remark</a
                    >
                    <span
                      v-if="sort_direction == 'desc' && sort_field == 'remark'"
                      >&uarr;</span
                    >
                    <span
                      v-if="sort_direction == 'asc' && sort_field == 'remark'"
                      >&darr;</span
                    >
                  </th>-->
                  <th>
                    <a href="#" @click.prevent="change_sort('status')">Action</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'status'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'status'">&darr;</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>{{ singleData.product_code }}</td>
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
                  <td>{{ singleData.type }}</td>
                  <td>
                    <span v-if="singleData.type_bn">{{ singleData.type_bn }}</span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.order_type">
                      {{
                      singleData.order_type.name_en
                      }}
                    </span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.packaging_type">{{ singleData.packaging_type }}</span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.weight">
                      {{ singleData.weight }}
                      {{ singleData.weight_type }}
                    </span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.quantity">{{ singleData.quantity }} (Pcs)</span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.tp">{{ singleData.tp }}</span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <!-- <td>{{ singleData.mrp }}</td> -->
                  <!-- <td>
                    <span v-if="singleData.remark">
                      {{ singleData.remark }}
                    </span>
                    <span v-else class="text-danger">N/A</span>
                  </td>-->
                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="(isPermit('product-order-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                      :style="(isPermit('product-order-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-order-edit')"
                      @click="
                        editDataModel(singleData, 'Update Orderable Product')
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>
                    <v-btn
                      v-if="isPermit('product-order-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>

                    <div class="text-muted text-center small">
                      Created By --
                      <span v-if="singleData.makby">{{ singleData.makby.name }}</span>
                      <span v-else class="error--text">N/A</span>
                    </div>

                    <div class="text-muted text-center small">
                      Last Sync --
                      <span v-if="singleData.updated_at">
                        {{
                        singleData.updated_at
                        | moment("MMM Do, YYYY , h:mm:ss a")
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

    <!-- dialog  -->
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
                <v-text-field
                  type="text"
                  v-model="form.product_code"
                  dense
                  label="Order Products Code"
                  placeholder="Enter Order Products Code"
                  :error-messages="form.errors.get('product_code')"
                  required
                  counter="100"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.type"
                  dense
                  label="Order Products Name (English)"
                  placeholder="Enter Order Products Name (English)"
                  :error-messages="form.errors.get('type')"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.type_bn"
                  dense
                  label="Order Products Name (Bangla)"
                  placeholder="Enter Order Products Name (Bangla)"
                  :error-messages="form.errors.get('type_bn')"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-autocomplete
                  v-model="form.category"
                  dense
                  label="Order Product Category"
                  placeholder="Select Order Product Category"
                  :items="category"
                  item-text="name_en"
                  item-value="id"
                  :error-messages="form.errors.get('category')"
                  required
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" lg="4">
                <v-autocomplete
                  v-model="form.packaging_type"
                  dense
                  label="Order Product Packaging Type"
                  placeholder="Select Order Product Packaging Type"
                  :items="packaging_type"
                  item-text="name"
                  item-value="name"
                  :error-messages="form.errors.get('packaging_type')"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" lg="4" v-if="form.packaging_type != 'Box'">
                <v-text-field
                  v-model="form.weight"
                  dense
                  label="Order Product Weight"
                  placeholder="Enter Order Product Weight"
                  :error-messages="form.errors.get('weight')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" lg="4" v-if="form.packaging_type != 'Box'">
                <v-select
                  v-model="form.weight_type"
                  label="Order Product Weight Unit"
                  placeholder="Select Order Product Weight Unit"
                  dense
                  :items="weight_type"
                  item-text="name"
                  item-value="name"
                  :error-messages="form.errors.get('weight_type')"
                  clearable
                ></v-select>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  type="number"
                  v-model="form.quantity"
                  dense
                  label="Order Product Quantity"
                  placeholder="Enter Order Product Quantity"
                  :error-messages="form.errors.get('quantity')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  type="number"
                  v-model="form.tp"
                  step="0.01"
                  dense
                  label="Order Product TP (Trade Price)"
                  placeholder="Enter Order Product TP (Trade PriceMRP "
                  :error-messages="form.errors.get('tp')"
                  required
                ></v-text-field>
              </v-col>
              <!-- <v-col cols="12" lg="4">
                <v-text-field
                  type="number"
                  v-model="form.mrp"
                  step="0.01"
                  dense
                  label="Order Product MRP"
                  placeholder="Enter Order Product MRP"
                  :error-messages="form.errors.get('mrp')"
                ></v-text-field>
              </v-col>-->
              <!-- <v-col cols="12" lg="4">
                <v-text-field
                  type="number"
                  v-model="form.gp"
                  dense
                  label="Order Product GP (Gross Price)"
                  placeholder="Enter Order Product GP (Gross Price)"
                  :error-messages="form.errors.get('gp')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="4">
                <v-text-field
                  v-model="form.gp_percentage"
                  dense
                  label="Order Product GP %"
                  placeholder="Enter Order Product GP %"
                  :error-messages="form.errors.get('gp_percentage')"
                ></v-text-field>
              </v-col>-->
              <!-- <v-col cols="12" lg="8">
                <v-text-field
                  v-model="form.remark"
                  dense
                  counter="5000"
                  label="Order Product Remark"
                  placeholder="Enter Order Product Remark (if any)"
                  :error-messages="form.errors.get('remark')"
                ></v-text-field>
              </v-col>-->
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
      currentUrl: "/admin/product/order_product",

      imagePathSm: "/images/order/small/",
      imagePath: "/images/order/",
      imageMaxSize: "2111775",

      // Form
      form: new Form({
        id: "",
        product_code: "",
        image: "",
        type: "",
        type_bn: "",
        category: "",
        packaging_type: "",
        weight: "",
        weight_type: "kg",
        quantity: "1",
        tp: "",
        mrp: "",
        gp: "",
        gp_percentage: "",
        remark: ""
      }),

      //  category
      category: "",

      //   packaging_type
      packaging_type: [
        {
          name: "Pack"
        },
        {
          name: "Box"
        },
        {
          name: "Cup"
        },
        {
          name: "Piece"
        },
        {
          name: "Bootle"
        }
      ],

      weight_type: [
        {
          name: "kg"
        },
        {
          name: "g"
        }
      ]
    };
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults();
    await this.getOrderProductTypeAsync();
    this.$Progress.finish();
  }
};
</script>

<style scoped>
a {
  text-decoration: none;
}

div[aria-required="true"].v-input .v-label::after {
  content: " *";
  color: red;
}
</style>

