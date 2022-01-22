FROM python:3


RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y curl \ 
	&& apt-get install -y nano \ 
	&& apt-get clean \ 
	&& rm -rf /var/lib/apt/lists/* 
RUN pip install psycopg2-binary
RUN pip install psycopg2

COPY . ./


CMD ["python","./pythonapi.py"]



##commands to docker //docker build -t dennystest . 
#                    //docker run -it dennystest