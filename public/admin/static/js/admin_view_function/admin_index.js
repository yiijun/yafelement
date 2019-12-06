var vm = new Vue({
    el: '#app',
    methods: {
        init:function () {
            $.post('/admin/index', {id:1}).done(function(response) {
                console.log(response)
                if(200 == response.code){
                    vm.options = response.data.role
                    vm.data    = response.data.list
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        onSubmit:function (form) {
            this.$refs[form].validate((valid) => {
                if (valid) {
                    $.post('/admin/addModification', vm.form).done(function(response) {
                        if(200 == response.code){
                            vm.$message.success(response.msg);
                            vm.init();
                            vm.activeName = 'first';
                            vm.clearForm();
                        }else{
                            vm.$message.error(response.msg);
                        }
                    })
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        onEdit:function (row) {
            console.log(row)
            vm.form.username = row.username
            vm.form.role_id  = row.role_id.toString()
            vm.form.id       = row.id
            vm.dialogFormVisible = true
        },
        onDelete:function (id) {
            this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'}).then(() => {
                $.post('/admin/delete', {id:id}).done(function(response) {
                    if(200 == response.code){
                        vm.$message.success(response.msg);
                        vm.init();
                        vm.activeName = 'first';
                        vm.clearForm();
                    }else{
                        vm.$message.error(response.msg);
                    }
                })
            }).catch(() => {
                this.$message({type: 'info', message: '已取消删除'});
            });
        },
        clearForm:function () {
            vm.dialogFormVisible = false;
            vm.form = {
                username:'',
                password:'',
                role_id:"",
                id:''
            }
        }
    }, mounted:function () {
        this.init();
    },

    data:{
        isCollapse: false,
        dialogVisible: false,
        dialogFormVisible:false,
        form:{
            username:'',
            password:'',
            role_id:"",
            id:''
        },
        options:[],
        data:[],
        formLabelWidth: '120px',
        rules: {
            username: [
                { required: true, message: '请输入用户名', trigger: 'blur' },
                { min: 2, max: 20, message: '长度在 2 到 20 个字符', trigger: 'blur' }
            ],
            password: [
                { required: true, message: '请输入密码', trigger: 'blur' },
                { min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur' }
            ],
            role_id: [
                { required: true, message: '请选择角色', trigger: 'change' }
            ],
        }
    },
})