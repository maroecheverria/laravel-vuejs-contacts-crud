<template>
    <div class="mt-5 mr-5 ml-5">
        <h1>Agenda de Contactos</h1>
        <hr />
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"/>
        <div class="modal" :class="{showModal:isModalVisible}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">{{modalTitle}}</h5>
                        <button @click="closeModal();" type="button" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label class="fa-pull-left mt-3" for="first-name">Nombre</label>
                            <input v-model="contact.first_name" type="text" class="form-control" id="first-name">
                        </div>
                        <div>
                            <label class="fa-pull-left mt-3" for="last-name">Apellido</label>
                            <input v-model="contact.last_name" type="text" class="form-control" id="last-name">
                        </div>
                        <div>
                            <label class="fa-pull-left mt-3" for="email">Email</label>
                            <input v-model="contact.email" type="text" class="form-control" id="email">
                        </div>
                        <div>
                            <label class="fa-pull-left mt-3" for="phones">Teléfonos (separados por comas)</label>
                            <input v-model="contact.phones" type="text" class="form-control" id="phones">
                        </div>
                        <div>
                            <label class="fa-pull-left mt-3" for="address">Domicilio</label>
                            <input v-model="contact.address" type="text" class="form-control" id="address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal();" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button @click="saveContact(contact);" type="button" class="btn btn-warning">{{modalAction}}</button>
                    </div>
                </div>
            </div>
        </div>
        <button @click="isUpdate=false; openModal();" type="button" class="btn btn-secondary fa-pull-right mb-3">
            Nuevo contacto
        </button>
        <div class="table-responsive fade-in hide">
            <table class="table table-striped table-bordered bootstrap-datatable datatable">
                <thead class="table-dark">
                    <tr>
                        <th class="col-md-1" scope="col">Id</th>
                        <th class="col-md-2" scope="col">Nombre</th>
                        <th class="col-md-2" scope="col">Apellido</th>
                        <th class="col-md-2" scope="col">Email</th>
                        <th class="col-md-2" scope="col">Teléfonos</th>
                        <th class="col-md-2" scope="col">Dirección</th>
                        <th class="col-md-1" scope="col" colspan="2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="!contacts.length">
                        <tr>
                            <td colspan="7">
                                No hay contactos
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="contact in contacts" :key="contact.id">
                            <th scope="row">{{ contact.id }}</th>
                            <td>{{ contact.first_name }}</td>
                            <td>{{ contact.last_name }}</td>
                            <td>{{ contact.email }}</td>
                            <td>{{ contact.phones }}</td>
                            <td>{{ contact.address }}</td>
                            <td>
                                <button @click="isUpdate=true; openModal(contact);" class="btn btn-warning">Editar</button>
                            </td>
                            <td>
                                <button @click="deleteContact(contact.id)" class="btn btn-danger">Eliminar</button>
                            </td>
                        </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        data() {
            return {
                isLoading: true,
                fullPage: true,
                contacts: [],
                contact: {
                    id: 0,
                    first_name: '',
                    last_name: '',
                    email: '',
                    phones: [],
                    address: '',
                },
                isUpdate: false,
                isModalVisible: false,
                modalTitle: '',
                modalAction: '',
                successMessage: '',
                errorMessage: '',
            }
        },
        components: {
            Loading
        },
        methods: {
            async listContacts() {
                try {
                    const res = await axios.get('contacts');
                    this.contacts = res.data.data;

                } catch (error) {
                    await this.handleAlertMessage(error.response.data.errorMessage, 'error');
                    console.log(error);
                }

                this.isLoading = false;
            },
            async saveContact() {
                try {
                    const res = this.isUpdate
                        ? await axios.put(`/contacts/${this.contact.id}`, this.contact)
                        : await axios.post(`/contacts`, this.contact)
                    await this.handleAlertMessage(res.data.message, 'success');
                } catch (error) {
                    await this.handleAlertMessage(error.response.data.errorMessage, 'error');
                    console.log(error);
                }
                await this.closeModal();
                await this.listContacts();
            },
            async deleteContact(id) {
                try {
                    const res = await axios.delete(`/contacts/${id}`);
                    await this.handleAlertMessage(res.data.message, 'success');
                } catch (error) {
                    await this.handleAlertMessage(error.response.data.errorMessage, 'error');
                    console.log(error);
                }
                await this.listContacts();
            },
            async openModal(data={}) {
                this.isModalVisible = true;
                this.modalTitle = (this.isUpdate ? "Modificar" : "Crear") + " contacto";
                this.modalAction = this.isUpdate ? "Guardar cambios" : "Confirmar";

                this.contact.id = data.id;
                this.contact.first_name = data.first_name;
                this.contact.last_name = data.last_name;
                this.contact.email = data.email;
                this.contact.phones = data.phones;
                this.contact.address = data.address;
            },
            async closeModal() {
                this.isModalVisible = false;
            },
            async handleAlertMessage(message, type) {
                Swal.fire({
                    html: Array.isArray(message) ? message.join("<br />") : message,
                    icon: type
                });
            },
        },
        created() {
            this.listContacts();
        },
    }
</script>
<style>
    .showModal {
        display: list-item;
        opacity: 1;
        background: rgba(44, 38, 75, 0.849);
    }
</style>
