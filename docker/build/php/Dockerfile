FROM nsign/php-cmf2-apache:7.1-dev

RUN cd /tmp \
    && apt-get update && apt-get install -y libreoffice-core \
    && wget http://download.documentfoundation.org/libreoffice/stable/5.3.7/deb/x86_64/LibreOffice_5.3.7_Linux_x86-64_deb.tar.gz \
    && tar -zxvf LibreOffice_5.3.7_Linux_x86-64_deb.tar.gz \
    && cd LibreOffice_*/DEBS \
    && dpkg -i *.deb
