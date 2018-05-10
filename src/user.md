## 1. 用户账号[user]

### 获取注册验证码[userRegGetCode]
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

### 注册[userRegister]
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

### 获取登陆验证码 [userLoginGetCode]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/login-get-code`
* 用途 
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

### 登陆[userLogin]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/login`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | username | string | 用户名，手机注册的为手机号 |
    | code | string | 短信验证码 |
    | source | string | 来源 0：默认 |
    | deviceId | string | 设备id |

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
            "status": 1
        }
    }
}
```

### 快速登陆[userQuickLogin]
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
            "status": 1
        }
    }
}
```

### 退出[userLogout]
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

### 修改登录密码[userChangePwd]
* 请求方式 `POST`
* 请求地址 `http://api.example.com/user/change-pwd`
* 用途 
* 请求参数 

    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | old_pwd | string | 原密码 |
    | new_pwd | string | 新密码 |

* 返回结果
```json
{
    "code": 0,
    "message": "修改成功",
    "data": {
        "item": true
    }
}
```