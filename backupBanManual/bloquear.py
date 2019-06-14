import connect

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

def bloquearUser (id):
	
	connection = connect.connectHost()
	
	saida = "\nNao foi possivel bloquear o usuario\n"
	try :
		
		cursor = connection.cursor()
		
		sql = "Update users set passwd = %s where ID = %s " 
		numUsers = cursor.execute(sql, ("BANIDO_GOT", id))
		
		if numUsers > 0:
			connection.commit()
			
			sql = "Select passwd from users Where ID = %s "

			# Execute sql, and pass 1 parameter.
			cursor.execute(sql, (id))
			
			for row in cursor:
				password = row["passwd"]
			
			if password == "BANIDO_GOT":
				saida = "\nUsuario bloqueado\n" 
		
		else:
			saida = "\nID invalido\n"
	finally:
    	# Close connection.
		connection.close()
	
	return saida

	
