import pymysql.cursors

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

#Connect to db

def connectHost(hostIn='35.247.202.126', userIn='root', passwordIn='elfica', dbIn='verificar', charsetIn='utf8mb4'):
	
	connection = pymysql.connect(host=hostIn, user=userIn, password=passwordIn, db=dbIn, charset=charsetIn, 
	cursorclass=pymysql.cursors.DictCursor)
	
	return connection

# Close connection.

def closeConnection():
	
    connection.close()
