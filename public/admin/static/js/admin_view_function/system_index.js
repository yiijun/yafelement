var vm = new Vue({
        el: '#app',
        data:{
            isCollapse: false,
            dialogVisible: false,
            activeName: 'first',
            imageUrl:'',
            form: {
                title: '',
                keywords: '',
                desc: '',
                concat:'',
                mobile:'',
                email:'',
                address:'',
                server_time:'',
                copyright:'',
                logo:'',
            }
        },methods: {
            onSubmit:function() {
                $.post('/system/save', vm.form).done(function(response) {
                    if(200 == response.code){
                        vm.$message.success(response.msg)
                        vm.getAllConfig();
                    }else{
                        vm.$message.error(response.msg);
                    }
                })
            },
            handleAvatarSuccess:function(response, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
                if(response.code == 200){
                    vm.form.logo = response.data.file_path;
                    vm.$message.success('上传图片成功');
                }else{
                    vm.$message.error(response.msg);
                }
            },
            beforeAvatarUpload:function(file) {
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isLt2M;
            },
            getAllConfig:function () {
                $.post('/system/index', {
                    data: {id:1}
                }).done(function(response) {
                    if(200 == response.code){
                        if(response.data){
                            vm.form = response.data;
                        }
                    }else{
                        vm.$message.error(response.msg);
                    }
                })
            }
        },
        mounted:function () {
            this.getAllConfig()
        }
    })