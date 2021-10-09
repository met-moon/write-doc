## 2. 其他

### 上传图片
* 请求方式 `POST`
* 请求地址 `http://api.example.com/upload/image`
* 用途 `上传图片`
* 请求参数
    
    | 名称 | 类型 | 注释 |
    |:-------------:|:-------------:|:-------------|
    | type | string | 1.头像 2.照片 3.背景图 |
    | attach | file | 文件 |

* 返回结果
```json
{
    "code": 0,
    "message": "上传成功",
    "data": {
        "url": "http://api.example.com/2/C50353842A435.jpg",
        "object": "2/C50353842A435.jpg",
        "host": "http://api.example.com/"
    }
}
```