    tail -n 40 file1.txt > file2.txt; head -n 10 file2.txt > file3.txt;
    sed -i 's/коко/куку /g' file2.txt; grep 'куку' file2.txt | head -n 3 > file3.txt; uniq -c file3.txt
 <br> </br>
 
 Терминал: 
 ![l1](/screen.png)
