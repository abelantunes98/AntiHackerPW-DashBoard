import pymysql.cursors

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

#Connect to db

def connectHost(hostIn='206.189.193.221', userIn='pwgot', passwordIn='XWNUI@78ondwa456', dbIn='pw', charsetIn='utf8mb4'):
	
	connection = pymysql.connect(host=hostIn, user=userIn, password=passwordIn, db=dbIn, charset=charsetIn, 
	cursorclass=pymysql.cursors.DictCursor)
	
	return connection

# Close connection.

def closeConnection():
	
    connection.close()

connectHost()
