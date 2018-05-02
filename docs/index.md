# API文档

> version: v1.0

> 接口地址： http://api.example.com/

## 文档说明
### APP接口固定参数
| 名称 | 类型 | 注释 |
|:-------------:|:-------------:|:-------------|
| clientType | string | 终端版本，取值范围： ios/android/pc/wap |
| appVersion | string | app版本，例如：1.0.0 |
| deviceName | string | 设备名称，例如：iphone7/xiaomi6 |
| osVersion | string | 设备os版本，例如：8.1/4.0.0 |

### 错误码规范
| 错误码 | 注释 |
|:-------------:|:-------------|
| >0 | 业务逻辑错误 |
| -1 | 系统通用错误，未指定具体错误码 |
| -2 | 未登录 |

## 目录
1. [用户账号](#1-user)
    1.1 [获取注册验证码](#11-userRegGetCode)
    1.2 [注册](#12-userRegister)
    1.3 [登录](#13-userLogin)
    1.4 [快速登录](#14-userQuickLogin)
    1.5 [退出](#15-userLogout)
    1.6 [修改登录密码](#16-userChangePwd)
    1.7 [初次设置交易密码](#17-userSetPaypassword)
    1.8 [修改交易密码](#18-userChangePaypassword)
    1.9 [获取找回登录密码/交易密码的验证码](#19-userResetPwdCode)
    1.10 [找回登录密码/交易密码验证用户和手机验证码](#110-userVerifyResetPassword)
    1.11 [找回交易密码密码时设置新密码](#111-userResetPayPassword)
    1.12 [找回登录密码时设置新密码](#112-userResetPassword)
    1.13 [验证当前手机号](#113-userVerifyCode)
2. [用户认证](#2)
3. [首页信息展示](#3)

## 1. 用户账号[user]

### 1.1 获取注册验证码[userRegGetCode]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/reg-get-code`
* 用途 `用户注册时拉取验证码`
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |

* 返回结果
```
{
    "code": 0,
    "message": "成功获取验证码",
    "data": {
        "item": []
    }
}
```

### 1.2 注册[userRegister]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/register`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |
    | code | string | 验证码 |
    | invite_code | string | 邀请码,6位字符 |
    | password | string | 密码 |
    | source | string | 来源 0：默认 |  
    | name | string | 姓名 |  

* 返回结果
```
{
    "code": 0,
    "message": "注册成功",
    "data": {
        "item": {
            "uid": 89669882,
            "username": "13812341234",
            "realname": "",
            "id_card": "",
            "real_verify_status": 0,
            "user_sign": null,
            "real_pay_pwd_status": 0,
            "real_work_status": 0,
            "real_contact_status": 0,
            "real_zmxy_status": 0,
            "real_bind_bank_card_status": 0,
            "sessionid": "br293s6i0p63m4i5lqshqh2c02",
            "card_list": []
        }
    }
}
```

### 1.3 登陆[userLogin]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/login`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | username | string | 用户名，手机注册的为手机号 |
    | password | string | 密码 |
    | source | string | 来源 0：默认 |

* 返回结果
```
{
    "code": 0,
    "message": "登录成功",
    "data": {
        "item": {
            "uid": 89669882,
            "username": "13812341234",
            "realname": "",
            "real_pay_pwd_status": 1,
            "real_verify_status": 1,
            "real_work_status": 1,
            "real_contact_status": 1,
            "real_zmxy_status": 1,
            "real_bind_bank_card_status": 1,
            "real_work_fzd_status": 0,
            "real_jxl_status": 0,
            "sessionid": "amqa5k5p67j0i8fpuvp7taec20",
            "card_list": [
                {
                    "card_id": 8821043,
                    "url": "http://api.example.com/image/bank/bank_8.png",
                    "bank_info": "招商银行储蓄卡 尾号4001",
                    "main_card": 1
                }
            ],
            "lqd_money": 1000,
            "lqd_text": "期限7~14天,最快半小时放款",
            "fzd_money": 1000,
            "fzd_text": "最快半小时放款",
            "fqg_money": 1000,
            "fqg_text": "最快半小时放款",
            "status": 1
        }
    }
}
```

### 1.4 快速登陆[userQuickLogin]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/quick-login`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | SESSIONID | string | session id |

* 返回结果
```
{
    "code": 0,
    "message": "登录成功",
    "data": {
        "item": {
            "uid": 89669882,
            "username": "13812341234",
            "realname": "",
            "real_pay_pwd_status": 1,
            "real_verify_status": 1,
            "real_work_status": 1,
            "real_contact_status": 1,
            "real_zmxy_status": 1,
            "real_bind_bank_card_status": 1,
            "real_work_fzd_status": 0,
            "real_jxl_status": 0,
            "sessionid": "amqa5k5p67j0i8fpuvp7taec20",
            "card_list": [
                {
                    "card_id": 8821043,
                    "url": "http://api.example.com/image/bank/bank_8.png",
                    "bank_info": "招商银行储蓄卡 尾号4001",
                    "main_card": 1
                }
            ],
            "lqd_money": 1000,
            "lqd_text": "期限7~14天,最快半小时放款",
            "fzd_money": 1000,
            "fzd_text": "最快半小时放款",
            "fqg_money": 1000,
            "fqg_text": "最快半小时放款",
            "status": 1
        }
    }
}
```

### 1.5 退出[userLogout]
* 请求方式 `GET`
* 请求地址 `http://api.example.com/user/logout`
* 用途 
* 请求参数 
* 返回结果
```
{
    "code": 0,
    "message": "成功退出",
    "data": {
        "result": true
    }
}
```

### 1.6 修改登录密码[userChangePwd]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/change-pwd`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | old_pwd | string | 原密码 |
    | new_pwd | string | 新密码 |

* 返回结果
```
{
    "code": 0,
    "message": "修改成功",
    "data": {
        "item": true
    }
}
```

### 1.7 初次设置交易密码[userSetPaypassword]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/set-paypassword`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | password | string | 交易密码 |

* 返回结果
```
{
    "code": 0,
    "message": "设置交易密码成功",
    "data": {
        "item": []
    }
}
```

### 1.8 修改交易密码[userChangePaypassword]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/change-paypassword`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | old_pwd | string | 原密码 |
    | new_pwd | string | 新密码 |

* 返回结果
```
{
    "code": 0,
    "message": "修改交易密码成功",
    "data": {
        "item": []
    }
}
```

### 1.9 获取找回登录密码/交易密码的验证码[userResetPwdCode]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/reset-pwd-code`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |
    | type | string | 类型：找回登录密码find_pwd，找回交易密码find_pay_pwd |

* 返回结果
```
{
    "code": 0,
    "message": "发送验证码成功",
    "data": {
        "item": true
    }
}
```

### 1.10 找回登录密码/交易密码验证用户和手机验证码[userVerifyResetPassword]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/verify-reset-password`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |
    | realname | string | 姓名 |
    | id_card | string | 身份证 |
    | code | string | 验证码 |
    | type | string | 类型：找回登录密码find_pwd，找回交易密码find_pay_pwd |

* 返回结果
```
{
    "code": 0,
    "message": "成功找回密码",
    "data": {
        "item": true
    }
}
```

### 1.11 找回交易密码密码时设置新密码[userResetPayPassword]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/reset-pay-password`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |
    | code | string | 验证码 |
    | password | string | 密码 |

* 返回结果
```
{}
```

### 1.12 找回登录密码时设置新密码[userResetPassword]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/reset-password`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | phone | string | 手机号 |
    | code | string | 验证码 |
    | password | string | 密码 |

* 返回结果
```
{}
```

### 1.13 验证当前手机号[userVerifyCode]

* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/verify-code`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | cur_phone | string | 当前手机号 |
    | cur_code | string | 当前手机号验证码 |

* 返回结果
```
{}
```

## 2. 用户认证

## 3. 首页信息展示




<script type="text/javascript">
    window.onload = function(){
        var h1 = document.getElementsByTagName('h1')[0];
        document.title = h1.textContent || h1.innerText;
        
        var a_list = document.getElementsByTagName("a");
        for(var i=0; i< a_list.length; i++){
            a_list[i].href = a_list[i].href.toLowerCase();
        }
    };
</script>