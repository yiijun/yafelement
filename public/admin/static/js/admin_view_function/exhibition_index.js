var vm = new Vue({
    el: '#app',
    methods: {
        handleChange:function(value){
            vm.positionForm.total_price = parseInt(value) * parseInt(vm.price)
        },
        handleTabClick(tab, event){
            switch (tab.name){
                case 'P':
                    this.getExPosition();
                    break;
            }
        },
        handleClose(index) {
            this.ex_positions.splice(index, 1);
        },
        getExPosition:function(){
            $.post('/exhibitionposition/index', {ex_id:vm.id}).done(function(response) {
                if(200 == response.code){
                    vm.ex_positions     = response.data.current_ex
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        showInput() {
            vm.dialogPositionVisible = true;
            vm.positionForm.ex_id = vm.id
        },
        handelDetail:function(row){
            vm.dialogDetailVisible = true
            vm.id = row.id
            vm.price = row.ex_price
            $.post('/exhibition/detail', {id:row.id}).done(function(response) {
                if(200 == response.code){
                    vm.ex_info     = response.data
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        init:function (page) {
            $.post('/exhibition/index', {page:page}).done(function(response) {
                if(200 == response.code){
                    vm.data     = response.data.list
                    vm.category = response.data.category
                    vm.total    = parseInt(response.data.total)
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        handelEdit:function(row){
            vm.form.title = row.title
            vm.form.address = row.address
            vm.form.unit = row.unit
            vm.form.ex_cycle = row.ex_cycle
            vm.form.ex_scope = row.ex_scope
            vm.form.ex_start_time = row.ex_start_time
            vm.form.ex_end_time = row.ex_end_time
            vm.form.ex_price = parseInt(row.ex_price)
            vm.form.staff_costs = parseInt(row.staff_costs)
            vm.form.category_id = row.category_id
            vm.form.id = row.id
            vm.dialogFormVisible = true
            setTimeout(function () {
                var editor = document.getElementById('editor');
                MarkdownIME.Enhance(editor);
                editor.innerHTML = row.content
            },300);
        },
        btnOpenWindow:function(){
            vm.dialogFormVisible = true
            setTimeout(function () {
                var editor = document.getElementById('editor');
                MarkdownIME.Enhance(editor);
            },300);
        },
        onCategoryLabel:function(action){
            let form = [];
            if(action == '/category/save'){
                form = vm.category_form
                if(vm.category_form.name==''){
                    vm.$message.error('请填写分类名称');return
                }
            }
            if(action == '/label/save'){
                form = vm.label_form
                if(vm.label_form.label_name==''){
                    vm.$message.error('请填写标签名称');return
                }
            }
            $.post(action, form).done(function(response) {
                if(200 == response.code){
                    vm.$message.success(response.msg);
                    vm.init();
                    vm.clearForm();
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        onSubmit:function (form) {
            if(editor.innerHTML!= ''){
                vm.form.content = editor.innerHTML
            }
            if(!vm.form.ex_price || vm.form.ex_price < 1){
                vm.$message.error('产品价格必须大于1');
                return
            }
            this.$refs[form].validate((valid) => {
                if (valid) {
                    vm.form.ex_time = JSON.stringify(vm.form.ex_time);
                    $.post('/exhibition/save', vm.form).done(function(response) {
                        if(200 == response.code){
                            vm.$message.success(response.msg);
                            vm.init();
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
        onSubmitPosition:function(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    $.post('/exhibitionposition/save', vm.positionForm).done(function(response) {
                        if(200 == response.code){
                            vm.$message.success(response.msg);
                            vm.init();
                            vm.clearForm();
                            vm.getExPosition();
                        }else{
                            vm.$message.error(response.msg);
                        }
                    })
                } else {
                    return false;
                }
            });
        },
        handelDelete:function(action,id){
            this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'}).then(() => {
                $.post(action, {id:id}).done(function(response) {
                    if(200 == response.code){
                        vm.$message.success(response.msg);
                        vm.init();
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
        clearForm:function () {
            vm.form = {
                title:'',
                address:'',
                unit:'',
                ex_cycle:'',
                ex_scope:'',
                ex_start_time:'',
                ex_end_time:'',
                ex_price:0,
                staff_costs:0,
                category_id: '5',
                content:'',
                id:''
            }
            vm.category_form = {
                name:''
            }
            vm.label_form = {
                label_name:''
            }
            vm.positionForm = {
                ex_id:0,
                position:'',
                status:'1',
                type:'info',
                size:0,
                total_price:0,
            }
            vm.dialogFormVisible = false
            vm.dialogPositionVisible = false
        }
    },mounted:function () {
        this.init();
    },data:{
        isCollapse: false,
        dialogVisible: false,
        dialogFormVisible:false,
        dialogDetailVisible:false,
        dialogPositionVisible:false,
        ex_positions:[],
        category:[],
        total:0,
        data:[],
        label:[],
        activeName:'first',
        activeDetail:'D',
        category_form:{
            name:''
        },
        id:'',
        price:0,
        ex_info:[],
        label_form:{
            label_name:''
        },
        form:{
            title:'',
            address:'',
            unit:'',
            ex_cycle:'一年一届',
            ex_scope:'',
            ex_number:0,
            ex_start_time:'',
            ex_end_time:'',
            ex_price:0,
            staff_costs:0,
            category_id: '5',
            content:'',
            id:''
        },
        positionForm:{
            ex_id:0,
            position:'',
            status:'1',
            type:'info',
            size:0,
            total_price:0,
        },
        rules: {
            title: [
                { required: true, message: '请输入展会名称', trigger: 'blur' },
            ],

            address:[
                { required: true, message: '请输入展会地址', trigger: 'blur' },
            ],
            unit:[
                { required: true, message: '请输入举办单位', trigger: 'blur' },
            ],
            ex_cycle:[
                { required: true, message: '请输入展会周期', trigger: 'blur' },
            ],
            ex_start_time:[
                { type: 'string', required: true, message: '请选择展会开始日期', trigger: 'change' }
            ],
            ex_end_time:[
                { type: 'string', required: true, message: '请选择展会结束日期', trigger: 'change' }
            ],
            category_id:[
                { required: true, message: '请选择分类', trigger: 'change' }
            ],
        },
        positionRules:{
            position:[
                { required: true, message: '位置名称不能为空', trigger: 'blur' },
            ],
            size:[
                { required: true, message: '位置大小不能为空', trigger: 'blur' },
            ],
            total_price:[
                { required: true, message: '价格不能为空', trigger: 'blur' },
            ],
        }
    },
})