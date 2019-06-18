import connect

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

def bloquearUser (idUser, nomeUser, tempoBan, motivoBan):
	
	connection = connect.connectHost()
	''' A ideia é adicionar os usuarios que devem ser bloqueados em uma tabela no
	banco de dados para que um loop no server bloqueie esse usuario.
	Isso é mais eficiente, já que o server já possui o script de ban'''


	saida = "\nNao foi possivel bloquear o usuario\n"
	try :
		
		cursor = connection.cursor()
		
		sql = "INSERT INTO banir ('idUser', 'nome', 'tempoSegundos', 'motivo') VALUES (%s, %s, %s, %s) "
		cursor.execute(sql, (idUser, nomeUser, tempoBan, motivoBan))
		connection.commit()

	except:

		return "Erro ao adicionar na tabela."

	try:
		sql = "Select idUser from banidos Where motivo is not null"
		# Execute sql, and pass 1 parameter.
		cursor.execute(sql)

		bloqueou = False
		for row in cursor:
			idBloq = row["idUser"]
			
			if idBloq == idUser:
				saida = "\nUsuario bloqueado com sucesso!\n"
				encontrou = True
				break

		if (not encontrou):
			saida = "\nProblema do lado do servidor ao bloquear.\n"

	finally:
    	# Close connection.
		connection.close()
	
	return saida

	
