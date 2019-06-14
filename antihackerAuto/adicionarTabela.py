import connectAutoBan
import pymysql.cursors 
 
# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br
 
connection = connectAutoBan.connectHost()
 
def adicionarNaTabela(idUser, name, motivo):
	 
	try :
		 
		cursor = connection.cursor()

		sql =  "INSERT INTO `Banidos` (`idUser`, `Name`, `Motivo`) VALUES (%s, %s, %s) "
		  
		# Execute sql, and pass 4 parameters.
		cursor.execute(sql, (idUser, name, motivo))
		 
		connection.commit() 
	 
	finally: 
		connection.close()


		

