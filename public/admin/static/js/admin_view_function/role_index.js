var vm = new Vue({
    el: '#app',
    methods: {
        init:function () {
            $.post('/role/index', {id:1}).done(function(response) {
                if(200 == response.code){
                    vm.data = response.data.role
                    vm.auth = response.data.auth
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        onSubmit:function (form) {
            var checkNode = this.$refs.tree.getCheckedKeys();
            if(checkNode.length < 1){
                vm.$message.error('必须选择权限');
                return
            }
            vm.form.auth_ids = JSON.stringify(checkNode)
            this.$refs[form].validate((valid) => {
                if (valid) {
                    $.post('/role/addModification', vm.form).done(function(response) {
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
                    return false;
                }
            });
        },
        onEditRole:function(row){
            vm.form       = row;
            vm.checkItem  = JSON.parse(row.auth_ids)
            vm.activeName = 'second';
            $("#tab-second").text('修改角色')
        },
        onDeleteRole:function(id){
            this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'}).then(() => {
                $.post('/role/delete', {id:id}).done(function(response) {
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
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });
            });
        },
        handleClickTab:function(tab, event){
            this.clearForm();
            this.$refs.tree.setCheckedKeys([]);
            if(tab.name=='first') $("#tab-second").text('新增角色')

        },
        clearForm:function () {
            vm.form = {
                role_name:'',
                role_desc:'',
                auth_ids:[],
                id:''
            }
            vm.checkItem = [];
        }
    },mounted:function () {
        this.init();
    },data:{
        isCollapse: false,
        dialogVisible: false,
        data:[],
        activeName:'first',
        form:{
            role_name:'',
            role_desc:'',
            auth_ids:[],
            id:''
        },
        auth:[],
        defaultProps: {
            children: 'child',
            label: 'title'
        },
        checkItem:[],
        rules: {
            role_name: [
                { required: true, message: '请输入角色名称', trigger: 'blur' },
                { min: 2, max: 20, message: '长度在 2 到 20 个字符', trigger: 'blur' }
            ],
            role_desc:[
                { required: true, message: '请输入角色描述', trigger: 'blur' },
            ],
        }
    },
})