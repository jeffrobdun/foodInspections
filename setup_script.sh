sudo apt-get install lamp-server^
sudo apt-get install git -y

mkdir ~/.ssh
chmod 700 ~/.ssh
ssh-keygen -t rsa

tail ~/.ssh/id_rsa.pub