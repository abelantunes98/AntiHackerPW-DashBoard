import pythonBan
import connectAutoBan
import adicionarTabela

# coding: utf-8
# Abel Antunes de Lima Neto - UFCG
# abel.neto@ccc.ufcg.edu.br

dictOnline = {}

connection = connectAutoBan.connectHost()

def deletePorIp(ip):

	try:
		cursor = connection.cursor()
		sql = "DELETE FROM info WHERE ip = %s" 
		cursor.execute(sql, ip)
		
		connection.commit()

	
	finally:
		
		return
		
def autoBan ():
		
	try :
		
		cursor = connection.cursor()
		
		# Pegando players online
		# Todos tem linkid = 1, mas na tabela so tem online
		
		sql = "Select userid, ipUser, name from players where linkid = %s " 
		numUsers = cursor.execute(sql, (1))
		
		if numUsers > 0:
			
			#Adiciona os online em um dict
			for row in cursor:
				
				dictOnline[row["ipUser"]] = (row["userid"], row["name"])
			
			for ip in dictOnline.keys():
				
				sql = "Select verificador, hackername from info where ip = %s "
				numUsers = cursor.execute(sql, (ip + "\n"))
				
				idUserIp = dictOnline.get(ip)[0]
				if numUsers == 0:
					
					numUsers = cursor.execute(sql, ip)
					
				if numUsers == 0:
					
					pythonBan.banPhp(idUserIp, 1, 120, "Verificador de seguranca excluido")
					deletePorIp(ip)
					adicionarTabela.adicionarNaTabela(idUserIp, dictOnline.get(ip)[1], "Verificador de seguranca excluido")
				
				else:
					
					for row in cursor:
				
						verificador = row["verificador"]
						hackername = row["hackername"]
					
					if verificador == "close": 
						
						pythonBan.banPhp(idUserIp, 1, 120, "Verificador de seguranca fechado")
						deletePorIp(ip)
						adicionarTabela.adicionarNaTabela(idUserIp, dictOnline.get(ip)[1], "Verificador de seguranca fechado")
					
					if hackername != "":
						
						pythonBan.banPhp(idUserIp, 1, 120, "Uso de Hack")
						deletePorIp(ip)
						adicionarTabela.adicionarNaTabela(idUserIp, dictOnline.get(ip)[1], ("Uso do Hack: " + hackername))
						
		sql = "Select ip, hackername from info where verificador = %s "
		numUsers = cursor.execute(sql, "close")
		
		if numUsers > 0:
			
			for row in cursor:
				
				ip = (row["ip"].split("\n"))[0]
				hackername = row["hackername"]
				
				if hackername != "":
					
					sql = "Select userid, name from playersfixo where ipUser = %s "
					numUsers = cursor.execute(sql, ip)
					
					if numUsers > 0:
						
						for row in cursor:
							idUser = row["userid"]
							playerName = row["name"]
							
							pythonBan.banPhp(idUserIp, 1, 120, "Uso de Hack")
							deletePorIp(ip)
							adicionarTabela.adicionarNaTabela(idUserIp, playerName, ("Uso do Hack: " + hackername))
							
				
	finally:
    	# Close connection.
		connection.close()
	
autoBan()
