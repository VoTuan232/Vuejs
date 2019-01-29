
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Category Table</h3>
                        <div class="card-tools">
                            <!-- <button class="btn btn-success" data-toggle="modal" data-target="#addNew">Add New -->
                            <button class="btn btn-success" @click="newModal">Add New
                            <i class="fas fa-user-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name_Vn</th>
                                    <th>Name_En</th>
                                    <th>Childrens</th>
                                    <th>Parents</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="category in categories.data" :key="category.id">
                                    <td>{{ category.id }}</td>
                                    <td>{{ category.name_vn }}</td>
                                    <td>{{ category.name_en }}</td>
                                    <td>
                                        <p v-for="children in category.childrens">
                                            {{ children.name_vn }}
                                        </p>
                                    </td>
                                    <td>
                                        <p v-for="parent in category.parents">
                                            {{ parent.name_vn }}
                                        </p>
                                    </td>
                                    <td>{{ category.created_at | changeCreatedDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(category)" title="edit">
                                        <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteCategory(category.id)" title="delete">
                                        <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination  :data="categories" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div id="addNew" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add New</h5>
                        <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateCategory() : createCategory()" @keydown="form.errors.clear()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name_vn" type="text" name="name_vn"
                                    placeholder="Name_Vn..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name_vn') }">
                                <has-error :form="form" field="name_vn"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.name_en" type="text" name="name_en"
                                    placeholder="Name_En..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name_en') }">
                                <has-error :form="form" field="name_en"></has-error>
                            </div>
                            <div class="form-group">
                                <multiselect 
                                v-model="form.childrens" 
                                :options="categoriesModel" 
                                :multiple="true" 
                                :close-on-select="false"
                                :clear-on-select="true" 
                                :hide-selected="true"
                                placeholder="Select Childrens" 
                                label="name_vn"
                                track-by="name_vn">
                                </multiselect>
                                <has-error :form="form" field="childrens"></has-error>
                            </div>
                            <div class="form-group">
                                <multiselect 
                                v-model="form.parents" 
                                :options="categoriesModel" 
                                :multiple="true" 
                                :close-on-select="false"
                                :clear-on-select="true" 
                                :hide-selected="true"
                                placeholder="Select Parents" 
                                label="name_vn"
                                track-by="name_vn">
                                </multiselect>
                                <has-error :form="form" field="parents"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                editMode : false,
                categories : {},
                childrens : [],
                parents : [],
                categoriesModel : [],
              // Create a new form instance
                form: new Form({
                    id : '',
                    name_vn : '',
                    name_en : '',
                    childrens : [],
                    parents : [],
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get(''+'/api/m/categories?page=' + page)
                    .then(response => {
                        this.categories = response.data;
                });
            },

            updateCategory() {
                this.form.put(''+'/api/m/category/' + this.form.id) //has id maybe for form.fill(user)
                .then(() => {
                    this.$Progress.start();
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Updated!',
                      'Updated Category successfully!',
                      'success'
                    )
                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                    this.$swal("Failed!", "There was something wrong!", "warning");
                });
            },

            editModal(category) {
                this.editMode = true;
                this.form.clear()
                this.form.reset();
                $("#addNew").modal("show");
                this.form.fill(category);
            },

            newModal() {
                this.editMode = false;
                this.form.clear()
                this.form.reset();  
                $("#addNew").modal("show");
            },

            deleteCategory(id) {
                this.$swal({
                      title: 'Are you sure?',
                      text: "You won't be able to revert this!",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.value) {
                        //send request api
                        this.form.delete(''+'/api/m/category/'+id)
                        .then(() => {
                             Fire.$emit('AfterCrud');
                                this.$swal(
                                  'Deleted!',
                                  'Category has been deleted.',
                                  'success'
                                )
                        })
                        .catch(() => {
                            this.$swal("Failed!", "There was something wrong!", "warning");
                        });
                    }
                })
            },

            loadCategories() {
                // if(this.$gate.isAdminorAuthor()) {
                    axios.get(''+'/api/m/categories').then(({ data }) => (this.categories = data));
                // }
            },

            loadCategoriesModel() {
                    axios.get(''+'/api/m/categories_model').then(({ data }) => (this.categoriesModel = data));
            },

            createCategory() {
                this.$Progress.start();
                this.form.post(''+'/api/m/category')
                .then(() => {
                    //call event
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Created!',
                      'Cretead Category successfully!',
                      'success'
                    )
                    this.$Progress.finish();
                })
                .catch(() => {  
                    this.$Progress.fail();
                    this.$swal("Failed!", "There was something wrong!", "warning");
                })

            }
        },
        created() {
            // Fire.$on('searching', () => {
            //     let query = this.$parent.search;
            //     axios.get('api/findUser?q=' + query)3
            //     .then((data) => {
            //         this.users = data.data;
            //     })
            //     .catch(() => {})
            // });
            this.loadCategories();
            this.loadCategoriesModel();
            // after event active
            Fire.$on('AfterCrud', () => {
                this.loadCategories();

            });
            //send request each 3s
            // setInterval(() => this.loadUsers(), 3000);
        }
        // mounted() {
        //     console.log('Component mounted.')
        // }
    }
</script>

