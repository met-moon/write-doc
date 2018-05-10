# API文档

> version: v1.0
> 接口地址： 
    - http://api.example.com/

## 文档说明
### APP接口固定参数

固定参数使用get方式传递

| 名称 | 类型 | 注释 |
|:-------------:|:-------------:|:-------------|
| clientType | string | 终端版本，取值范围： ios/android/pc/wap |
| appVersion | string | app版本，例如：1.0.0 |
| deviceName | string | 设备名称，例如：iphone7/xiaomi6 |
| osVersion | string | 设备os版本，例如：11.2/8.0.0 |

### 错误码规范
| 错误码 | 注释 |
|:-------------:|:-------------|
| >0 | 业务逻辑错误 |
| -1 | 系统通用错误，未指定具体错误码 |
| -2 | 未登录 |

## 目录
<!-- menu -->