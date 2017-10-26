sudo apt-get install lamp-server^
# DB password in config when prompted
sudo apt-get install git -y
sudo apt-get install tesseract-ocr -y

mkdir ~/.ssh
chmod 700 ~/.ssh
ssh-keygen -t rsa

tail ~/.ssh/id_rsa.pub
