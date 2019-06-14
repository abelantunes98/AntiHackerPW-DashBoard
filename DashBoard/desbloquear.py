import connect

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

def desbloquearUser (id):
	
	connection = connect.connectHost()
	sql = "Select passwd from users Where ID = %s "
	saida = "Esse usuario nao esta bloqueado!";
	
	try :
		
		cursor = connection.cursor()
		sql = "Select passwd from users Where ID = %s "

		# Execute sql, and pass 1 parameter.
		numUsers = cursor.execute(sql, (id))
		
		if numUsers > 0:
			
			for row in cursor:
				password = row["passwd"]
		
			if password == "BANIDO_GOT":
	
				sql = "Select passwd2 from users Where ID = %s "
				# Execute sql, and pass 1 parameter.
				cursor.execute(sql, (id))
			
				for row in cursor:
					senha = row["passwd2"]
			
				sql = "Update users set passwd = %s where ID = %s " 
				cursor.execute(sql, (senha, id))
		
				connection.commit()
			
				sql = "Select passwd from users Where ID = %s "

				# Execute sql, and pass 1 parameter.
				cursor.execute(sql, (id))
		
				for row in cursor:
					password = row["passwd"]
			
				if password != "BANIDO_GOT":
					saida = "Usuario desbloqueado!"
			
				else:
					saida = "Nao foi possivel desbloquear o usuario!"
		else: 
			saida = "ID invalido!"
			
	finally:
    	# Close connection.
		connection.close()
	
	return saida
	
