<template>
    <div ref="avatar-cropper" class="avatar-cropper">
        <img :src="previewSource" width="200" class="avatar-cropper-preview"
             @click="avatarClick">
        <input type="file" name="avatar" class="form-control sr-only" accept="image/jpeg,image/jpg,image/png"
               ref="file-upload" @change="fileChanged">

        <div class="modal fade avatar-cropper-modal" ref="avatar-cropper-modal" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Avatar Cropper</h4>
                        <button type="button" class="close" @click="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <img :src="cropperSource" width="100%" ref="avatar-cropper-image">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" @click="close">Close</button>
                        <button type="button" class="btn btn-primary" @click="crop">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</template>

<script>
    import Cropper from 'cropperjs'

    export default {
        name: "AvatarCropper",
        props: {
            defaultPreviewSource:{
               type: String,
               default: '/storage/avatars/no-image.png'
           }
        },
        data() {
            return {
                cropper: null,
                cropperSource: '',
                previewSource: this.defaultPreviewSource
            }
        },
        mounted() {
            const vm = this;
            $(this.$refs['avatar-cropper-modal']).on('hide.bs.modal', function (e) {
                e.stopPropagation();
            });
            $(this.$refs['avatar-cropper-modal']).on('shown.bs.modal', function () {
                const image = vm.$refs['avatar-cropper-image'];
                vm.cropper = new Cropper(image, {
                    responsive: true,
                    autoCropArea: 1,
                    aspectRatio: 1,
                    viewMode: 3,
                });
            });
        },
        methods: {
            close() {
                $(this.$refs['avatar-cropper-modal']).modal('hide');
            },
            avatarClick() {
                this.$refs['file-upload'].click()
            },
            fileChanged(event) {
                const vm = this;

                let file = event.target.files[0];
                let reader;

                if (file) {
                    if (URL) {
                        this.cropperSource = URL.createObjectURL(file);
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function (e) {
                            vm.cropperSource = reader.result;
                        };
                    }
                }

                $(this.$refs['avatar-cropper-modal']).modal('show');
            },
            crop(){
                const vm = this;
                if(this.cropper){

                    this.previewSource = this.cropper.getCroppedCanvas({
                        width: 200,
                        height: 200,
                    }).toDataURL();

                    this.cropper.getCroppedCanvas().toBlob(function (blob) {
                        vm.$emit('updateBlob',blob);
                    });

                    this.close();

                    vm.cropper.destroy();
                }
            }
        }
    }
</script>

<style scoped>
    .avatar-cropper-preview {
        cursor: pointer;
    }

    .avatar-cropper-modal {
        z-index: 99;
    }
</style>
