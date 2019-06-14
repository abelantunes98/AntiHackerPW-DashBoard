#import connect
#import bloquear
#import desbloquear
import sys
import os

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

def listarBloqueados():
	
	saida = ""
	connection = connect.connectHost()
	
	try:
		
		cursor = connection.cursor()
		sql = "Select ID, name from users Where passwd = %s "

		# Execute sql, and pass 1 parameter.
		numUsers = cursor.execute(sql, ("BANIDO_GOT"))
		
		if numUsers > 0:
			
			for row in cursor:
				print "ID:", row["ID"], " Nome:", row["name"]
		
		else:
			
			print "Nenhum usuario bloqueado!"
			
	finally:
    	# Close connection.
		connection.close()


def imprimeMenu():
	
	os.system('cls' if os.name == 'nt' else 'clear')
	
	print " ____              __  __        "
	print "|  _ \            |  \/  |                       | |"
	print "| |_) | __ _ _ __ | \  / | __ _ _ __  _   _  __ _| |"
	print "|  _ < / _` | '_ \| |\/| |/ _` | '_ \| | | |/ _` | |"
	print "| |_) | (_| | | | | |  | | (_| | | | | |_| | (_| | |"
	print "|____/ \__,_|_| |_|_|  |_|\__,_|_| |_|\__,_|\__,_|_|\n\n"
	
	print "O que voce deseja fazer ?\n"
	print "(B)loquear"
	print "(D)esbloquear"
	print "(L)istar Usuarios Bloqueados\n\n"
	
def voltaMenu():
	opcao = raw_input("\nDeseja fazer mais alguma coisa? (S)im ou (N)ao? ")
	opcao = opcao.lower()
	
	if opcao == "s":
		main()
	
	else:
		sys.exit(0)
	
def main():
	
	imprimeMenu()
	opcao = raw_input()
	
	opcao = opcao.lower()
		
	if opcao == "b":
		
		idUser = raw_input("\nInforme o ID do usuario: ")
		result = bloquear.bloquearUser(idUser)
		
		print result
	
	elif opcao == "d":
		
		idUser = raw_input("\nInforme o ID do usuario: ")
		result = desbloquear.desbloquearUser(idUser) 
		
		print "\n" + result
	
	elif opcao == "l":
		
		print""
		listarBloqueados()
	
	else:
		print "\nOpcao invalida"
		
	voltaMenu()
	
main()
