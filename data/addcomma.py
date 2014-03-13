import re
fo = open("uni.txt","r")

while(fo.readline()):
        txt=fo.readline()
        txt = re.sub("\n","",txt)
        txt ='"' +txt+'",'
        print txt
        fe=open("uni_temp2.txt","a")
        fe.write(txt)
        fe.close()

fo.close()
print "done!"
