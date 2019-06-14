import os
import commands

def escrever(idUser, tempo1, tempo2, motivo):
	
	timeB = "$timeBan = " + str(tempo1) + ";"
	idUser = "$idUser = " + str(idUser) + ";"
	multiplicador = "$mult = " + str(tempo2) + ";"
	motivo = "$motivo = \"" + motivo + "\"" + ";"
	
	arquivo = open('dadosBan.php', 'w')
	conteudo = ["<?php\n", timeB + "\n", idUser + "\n", multiplicador + "\n", motivo + "\n", "?>"]
	arquivo.writelines(conteudo)
	arquivo.close()
	
def banPhp(idUser, tempo1, tempo2, motivo): 
	
	escrever(idUser, tempo1, tempo2, motivo)
	os.system("php /var/www/html/Scripts/antiHackerpy/ban.php")

banPhp(1200, 6, 60, "aaap")

