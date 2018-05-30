## 重点推荐:
[github khs1994-docker/lnmp](https://github.com/khs1994-docker/lnmp)

生产机真正可以使用的 LNMP Docker 部署项目.

我通过这个项目学习了很多关于 Docker 的知识. 作者也十分的友好. 可以不给我 star, 但是这个项目必须得点个赞.

## 项目概述

本项目同样也是 LNMP Docker 的部署项目, 是在学习 Docker 过程中实现的. 目前还是在开发阶段, 只完成了基本的功能. 后续会持续更新.

## 最终目的

完成可以在生产机中使用的 LNMP Docker 环境.

## 项目细节

### Nginx

注释全面. 方便理解和学习使用. 适用于像我一样的初学者.

```yaml
# 这个指的是 docker-compose 的版本? 待确定.
version: "3.5"
services:
  # nginx 的配置路径可以查看 https://github.com/nginxinc/docker-nginx/blob/dbd053d52727bc8db0fec704caa22b8e0d5f6c84/mainline/alpine/Dockerfile
  nginx:
    # 同时设置了 build 和 image, build 后以 image 的定义命名. 例如这个示例, image 的名称是 qiuyuhome/nginx:1.13.9-alpine
    build:
      # 指定 build 的路径.
      context: ./dockerfile.d/nginx
      # args:
      #  version: ${NGINX_VERSION}

    image: ${IMAGE_PREFIX}/nginx:${NGINX_VERSION}
    # 暴露容器端口到主机的任意端口或指定端口, 其他容器和主机都可以使用这个端口.
    ports:
      # 0.0.0.0是任意ip
      - ${NGINX_PORT}:80
    # labels 的作用是为了方便刷选容器. docker ps -f labels=com.qiuyuhome.lnmp, 详见我发起的问题: https://github.com/khs1994-docker/lnmp/issues/282
    labels:
      - ${LABEL_PRIFIX}.nginx=true
      - ${LABEL_PRIFIX}.lnmp=true
    # 数据卷所挂载路径设置, 可以设置宿主机路径（HOST:CONTAINER）或加上访问模式（HOST:CONTAINER:ro）,由（:）分隔的三个字段组成，<卷名>:<容器路径>:<选项列表>。选项列表，如：ro只读
    volumes:
      # 注意权限问题. 这个权限, 指的是容器中对挂在的路径所拥有的权限. 例如下面的只读的例子, 在容器中, 在/etc/nginx/conf.d下创建文件是没有权限的, 因为没有写入的权限.
      # 容器中的路径, 如果没有这个路径, 会自动创建, 如第一个例子, 会自动创建/app
      - ./app:/app:rw
      - ./config/nginx:/etc/nginx:rw
      - ./logs/nginx:/var/log/nginx:rw
    # 与 docker run -w 的效果一样. 指定当前的工作目录. 简单来说就是启动容器之后, 容器的当前目录就会是设置的这个目录. 经测试, 会自动创建这个目录
    working_dir: /app
    # 指定一个自定义容器名称, 而不是生成的默认名称, 由于Docker容器名称必须是唯一的，因此如果您指定了自定义名称，则无法将服务扩展到1个容器之外.
    container_name: lnmp-nginx
    # 与 docker run --restart 的效果一样. 可以设置三个值. no(默认值):容器退出时不重启, on-failure: 容器故障退出（返回值非零）时重启, always: 容器退出时总是重启
    restart: always
    # 网络
    networks:
      - backend
    # 传递给容器的环境变量, 注意, 这里是key, value, 用等号连接.
    environment:
      - TZ=Asia/Shanghai
    depends_on:
      - php7
```

### PHP

常用的扩展都已经安装了. 包括 xdebug 的配置. 方便开发调试.

### MySQL
* user: root
* pwd: root
* port: 3306
* dbname: test

### PostgreSQL
* user: postgres
* pwd: root
* port: 5432
* dbname: postgres

### Redis


### PHPMyAdmin

* 127.0.0.1:8080

## 常用命令

### 杀死所有正在运行的容器
`docker kill $(docker ps -a -q)`

### 删除所有已经停止的容器
`docker rm $(docker ps -a -q)`


### 清理所有停止的容器
`docker container prune`

### 清理所有不用数据(停止的容器,不使用的volume,不使用的networks,悬挂的镜像)
`docker system prune -a`

