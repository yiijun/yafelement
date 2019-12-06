var checkMobile = (rule,value,callback) => {
    var pattern = /^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/
    if(!pattern.test(value)){
        callback(new Error('错误的手机号码'));
    }else{
        callback();
    }
};

var checkEmail = (rule,value,callback) => {
    var pattern = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    if(!pattern.test(value)){
        callback(new Error('错误的电子邮箱'))
    }else{
        callback();
    }
}
var vm = new Vue({
    el: '#app',
    methods: {
        handelExPosition:function(ex_id){
            console.log(ex_id)
            $.post('/exhibitionposition/index',{ex_id:ex_id}).done(function (response) {
                if(200 == response.code){
                    vm.ex_positions = response.data.current_ex
                    vm.current_row  = response.data.current_row
                    if(vm.current_row){
                        vm.is_per_disable = false;
                    }
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        handelSignUp:function(row){
            vm.signUpForm.uid = row.uid
            $.post('/exhibition/current',{id:1}).done(function (response) {
                if(200 == response.code){
                    vm.current_ex = response.data
                    vm.dialogSignUpVisible = true;
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        btnOpenWindow:function(){
            vm.dialogFormVisible = true
        },
        init:function () {
            $.post('/user/index', {id:1}).done(function(response) {
                if(200 == response.code){
                    vm.list     = response.data.list
                    vm.total    = parseInt(response.data.total)
                }else{
                    vm.$message.error(response.msg);
                }
            })
        },
        onSubmitRemark(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    $.post('/userremark/save', vm.remarkForm).done(function(response) {
                        if(200 == response.code){
                            vm.$message.success(response.msg);
                            vm.activeRemarkName = 'first';
                            vm.handelFollow(vm.remarkForm.uid)
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
        onSubmit:function (form) {
            this.$refs[form].validate((valid) => {
                if (valid) {
                    $.post('/user/save', vm.form).done(function(response) {
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
        onSubmitSignUp:function(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    let ex_position_ids = "";
                    $.each(vm.signUpForm.ex_position_ids,function (k,v) {
                        ex_position_ids += v+",";
                    });
                    vm.signUpForm.ex_position_ids = ex_position_ids.substring(0,ex_position_ids.length-1)
                    $.post('/userexhibition/sign', vm.signUpForm).done(function(response) {
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

        handelFollow:function(uid){
            $.post('/userremark/index', {uid:uid}).done(function(response) {
                if(200 == response.code){
                    vm.remark     = response.data.remark
                    vm.dialogRemarkVisible = true
                    vm.remarkForm.uid = uid
                }else{
                    vm.$message.error(response.msg);
                }
            })

        },
        handelSelectPosition:function(row){
            if(vm.signUpForm.ex_position_ids.indexOf(row.id) >= 0){
                vm.pos_total_price += parseInt(row.total_price)
            }else{
                vm.pos_total_price -= parseInt(row.total_price)
            }
            if(vm.signUpForm.ex_position_ids.length > 0){
                vm.is_dis = false;
            }
        },
        handelExDisPrice:function(v){
            if(vm.pos_total_price && vm.pos_total_price > 1){
                vm.signUpForm.ex_tran_price = vm.pos_total_price - parseInt(v)
            }
        },
        handelPerson:function(value){
            if(value >= 1){
                vm.is_dis_disable = false;
            }
            vm.per_total_price = parseInt(vm.current_row.staff_costs) * parseInt(value)
        },
        handelPerDisPrice:function(value){
            console.log(value)
            vm.signUpForm.per_tran_price = parseInt(vm.per_total_price) - value;
        },
        handelLook:function(row){
            vm.dialogLookVisible = true
            console.log(row)
            vm.user_row = row

        },
        clearForm:function () {
            vm.form = {
                company:'',
                source:'拨打电话',
                status:'',
                sex:"3",
                position:"员工",
                name:"",
                email:"",
                mobile:"",
                qq:"",
                wechat:"",
                tel:"",
                address:"",
                uid:"",
                admin_id:admin_id,
            }
            vm.remarkForm.remark = '';
            vm.signUpForm={
                ex_id:'',
                uid:'',
                ex_position_ids:[],
                per_tran_price:0,
                per_dis_price:0,
                number:0,
                ex_tran_price:0,
                ex_dis_price:0,
            }
            vm.dialogSignUpVisible = false;
            vm.dialogFormVisible   = false;
        }
    },mounted:function () {
        this.init();
    },data:{
        isCollapse: false,
        dialogVisible: false,
        dialogFormVisible:false,
        dialogRemarkVisible:false,
        dialogSignUpVisible:false,
        dialogLookVisible:false,
        list:[],
        remark:[],
        ex_positions:[],
        total:0,
        pos_total_price:0,
        per_total_price:0,
        current_ex:[],
        is_dis:true,
        is_per_disable:true,
        is_dis_disable:true,
        current_row:[],
        user_row:[],
        activeUserTab:'first',
        activeRemarkName:'first',
        form:{
            company:'',
            source:'拨打电话',
            status:'',
            sex:"3",
            position:"员工",
            name:"",
            email:"",
            mobile:"",
            qq:"",
            wechat:"",
            tel:"",
            address:"",
            uid:"",
            admin_id:admin_id,
        },
        remarkForm:{
            admin_id:admin_id,
            remark:'',
            type:'微信',
            id:'',
            uid:'',
        },
        signUpForm:{
            ex_id:'',
            uid:'',
            ex_position_ids:[],
            per_tran_price:0,
            per_dis_price:0,
            number:0,
            ex_tran_price:0,
            ex_dis_price:0,
        },
        rules: {
            company: [
                { required: true, message: '请输入公司名称', trigger: 'blur' },
            ],
            name: [
                { required: true, message: '请输入联系人名称', trigger: 'blur' },
            ],
            position: [
                { required: true, message: '选择联系人职务', trigger: 'blur' },
            ],
            mobile: [
                { required: true, message: '输入联系人电话', trigger: 'blur' },
                { min: 11, max: 11, message: '输入11位的手机号码', trigger: 'blur' },
                {validator:checkMobile,trigger: 'blur' },
            ],
            status:[
                { required: true, message: '选择客户状态', trigger: 'change' }
            ],
            email:[
                {validator:checkEmail,trigger: 'blur' },
            ]
        },
        remarkRule:{
            remark: [
                { required: true, message: '填写跟进信息', trigger: 'blur' },
            ]
        },
        signUpRules:{
            ex_id: [
                { required: true, message: '请选择订购展会', trigger: 'change' }
            ],
            ex_position_ids: [
                { type: 'array', required: true, message: '请至少选择一个展会位置', trigger: 'change' }
            ],
        }
    },
})
