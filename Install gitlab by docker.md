## Docker 安装 gitlab

### 首先下载官方镜像
`docker pull gitlab/gitlab-ce:latest`

### 运行

```
sudo docker run --hostname my.gitlab.com \
    --publish 10443:443 --publish 10080:80 --publish 10022:22 \
    --name gitlab \
    --restart always \
    --volume /Users/qiuyu/gitlab/config:/etc/gitlab \
    --volume /Users/qiuyu/gitlab/logs:/var/log/gitlab \
    --volume /Users/qiuyu/gitlab/data:/var/opt/gitlab \
    gitlab/gitlab-ce:latest
```
