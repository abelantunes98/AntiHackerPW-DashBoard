import connect

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

def desbloquearUser (idUser):
	connection = connect.connectHost()
	''' A ideia é adicionar os usuarios que devem ser desbloqueados em uma tabela no
    banco de dados para que um loop no server desbloqueie esse usuario.
    Isso é mais eficiente, já que o server já possui o script de ban'''

	saida = "\nNao foi possivel desbloquear o usuario\n"
	try:

		cursor = connection.cursor()

		sql = "INSERT INTO desbanir ('idUser') VALUES (%s) "
		cursor.execute(sql, (idUser))
		connection.commit()

	except:

		return "Erro ao adicionar na tabela."

	try:
		sql = "Select idUser from banidos Where motivo is not null"
		# Execute sql, and pass 1 parameter.
		cursor.execute(sql)

		desbloqueou = True
		for row in cursor:
			idBloq = row["idUser"]

			if idBloq == idUser:
				saida = "\nErro ao remover usuario da tabela de banidos.\n"
				desbloqueou = False
				break

		if (desbloqueou):
			saida = "\nUsuario desbloqueado com sucesso!\n"

	finally:
		# Close connection.
		connection.close()

	return saida
	
