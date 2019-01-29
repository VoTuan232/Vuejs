
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Table</h3>
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
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Register At</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        <p v-for="role in user.roles">
                                            {{ role.name }}
                                        </p>
                                    </td>
                                    <td>{{ user.created_at | changeCreatedDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(user)">
                                        <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination  :data="users" @pagination-change-page="getResults"></pagination>
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
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                    placeholder="Name..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                    placeholder="Email..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" id="bio"
                                    placeholder="Short bio for users (Optional)..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <multiselect 
                                v-model="form.roles" 
                                :options="roles" 
                                :multiple="true" 
                                :close-on-select="false"
                                :clear-on-select="true" 
                                :hide-selected="true"
                                placeholder="Select User Role" 
                                label="name"
                                track-by="name">
                                </multiselect>
                             <!-- <v-select multiple  :options="roles.name" ></v-select> -->
                                <!-- <v-select  v-model="form.roles" name="roles" id="roles"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('roles') }">
                                    <option value="">Select User Role</option>
                                    <option v-for="role in roles" v-bind:value="role.id"  :key = "role.id">
                                        {{ role.name }}
                                    </option>
                                </v-select> -->
                                <has-error :form="form" field="roles"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password"
                                    placeholder="Password..." 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
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
                users : {},
                roles : [],
              // Create a new form instance
                form: new Form({
                    id : '',
                    name : '',
                    email : '',
                    password : '',
                    bio : '',
                    photo : '',
                    roles : [],
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get(''+'/api/m/users?page=' + page)
                    .then(response => {
                        this.users = response.data;
                });
            },

            updateUser() {
                // console.log(this.form.name);
                this.form.put(''+'/api/m/user/' + this.form.id) //has id maybe for form.fill(user)
                .then(() => {
                    this.$Progress.start();
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Updated!',
                      'Updated User successfully!',
                      'success'
                    )
                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                    this.$swal("Failed!", "There was something wrong!", "warning");
                });
            },

            editModal(user) {
                this.editMode = true;
                this.form.clear()
                this.form.reset();
                $("#addNew").modal("show");
                this.form.fill(user);
            },

            newModal() {
                this.editMode = false;
                this.form.clear()
                this.form.reset();
                $("#addNew").modal("show");
            },

            deleteUser(id) {
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
                        this.form.delete(''+'/api/m/user/'+id)
                        .then(() => {
                             Fire.$emit('AfterCrud');
                                this.$swal(
                                  'Deleted!',
                                  'User has been deleted.',
                                  'success'
                                )
                        })
                        .catch(() => {
                            this.$swal("Failed!", "There was something wrong!", "warning");
                        });
                    }
                })
            },

            loadUsers() {
                // if(this.$gate.isAdminorAuthor()) {
                    axios.get(''+'/api/m/users').then(({ data }) => (this.users = data));
                // }
            },

            loadRoles() {
                    axios.get(''+'/api/m/roles/all').then(({ data }) => (this.roles = data));
            },

            createUser() {
                this.$Progress.start();
                this.form.post(''+'/api/m/user')
                .then(() => {
                    //call event
                    Fire.$emit('AfterCrud');
                    $('#addNew').modal('hide');
                    this.$swal(
                      'Created!',
                      'Cretead User successfully!',
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
            this.loadUsers();
            this.loadRoles();
            // after event active
            Fire.$on('AfterCrud', () => {
                this.loadUsers();
            });
            //send request each 3s
            // setInterval(() => this.loadUsers(), 3000);
        }
        // mounted() {
        //     console.log('Component mounted.')
        // }
    }
</script>

