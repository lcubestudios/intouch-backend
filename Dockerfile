FROM php:7.4-cli

ENV WORK_DIR=/php/

RUN mkdir -p ${WORK_DIR}
WORKDIR ${WORKDIR}

COPY . ${WORK_DIR}

CMD [ "php", "./index.php" ]