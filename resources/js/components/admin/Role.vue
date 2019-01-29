
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Role Table</h3>
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
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="role in roles.data" :key="role.id">
                                    <td>{{ role.id }}</td>
                                    <td>{{ role.name }}</td>
                                    <td>{{ role.display_name }}</td>
                                    <td>{{ role.description }}</td>
                                    <td>{{ role.created_at | changeCreatedDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(role)" title="edit">
                                        <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteRole(role.id)" title="delete">
                                        <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination  :data="roles" @pagination-change-page="getResults"></pagination>
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
                    <form @submit.prevent="editMode ? updateRole() : createRole()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                    placeholder="Name..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.display_name" type="text" name="display_name"
                                    placeholder="Display name..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('display_name') }">
                                <has-error :form="form" field="display_name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.description" type="text" name="description"
                                    placeholder="Description..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                                <has-error :form="form" field="description"></has-error>
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
                roles : {},
              // Create a new form instance
                form: new Form({
                    id : '',
                    name : '',
                    display_name : '',
                    description : '',
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get(''+'/api/m/roles?page=' + page)
                    .then(response => {
                        this.roles = response.data;
                });
            },

            updateRole() {
                this.form.put(''+'/api/m/role/' + this.form.id) //has id maybe for form.fill(user)
                .then(() => {
                    this.$Progress.start();
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Updated!',
                      'Updated Role successfully!',
                      'success'
                    )
                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                    this.$swal("Failed!", "There was something wrong!", "warning");
                });
            },

            editModal(role) {
                this.editMode = true;
                this.form.clear()
                this.form.reset();
                $("#addNew").modal("show");
                this.form.fill(role);
            },

            newModal() {
                this.editMode = false;
                this.form.clear()
                this.form.reset();  
                $("#addNew").modal("show");
            },

            deleteRole(id) {
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
                        this.form.delete(''+'/api/m/role/'+id)
                        .then(() => {
                             Fire.$emit('AfterCrud');
                                this.$swal(
                                  'Deleted!',
                                  'Role has been deleted.',
                                  'success'
                                )
                        })
                        .catch(() => {
                            this.$swal("Failed!", "There was something wrong!", "warning");
                        });
                    }
                })
            },

            loadRoles() {
                // if(this.$gate.isAdminorAuthor()) {
                    axios.get(''+'/api/m/roles').then(({ data }) => (this.roles = data));
                // }
            },

            createRole() {
                this.$Progress.start();
                this.form.post(''+'/api/m/role')
                .then(() => {
                    //call event
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Created!',
                      'Cretead Role successfully!',
                      'success'
                    )
                    this.$Progress.finish();
                })
                .catch(() => {  
                    this.$Progress.fail();
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
            this.loadRoles();
            // after event active
            Fire.$on('AfterCrud', () => {
                this.loadRoles();
            });
            //send request each 3s
            // setInterval(() => this.loadUsers(), 3000);
        }
        // mounted() {
        //     console.log('Component mounted.')
        // }
    }
</script>

