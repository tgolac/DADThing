/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dadproject;

import java.util.Scanner;

/**
 *
 * @author Korisnik
 */
public class DADProjectMain {
    
    public static void main(String[] args) throws ClassNotFoundException
    {
        DADProject test = new DADProject(args[0], args[1], "");
        
        Scanner scan = new Scanner(System.in);
        System.out.print("Select Demo(1 for ex2, 2 for ex3, any other number for exit): ");
        int testSelect = scan.nextInt();
        System.out.println();
        
        switch (testSelect) {
            case 1:
                test.connect();
                test.psSelect();
                test.psSelect2();
                test.selectAttendee();
                test.insert(false);
                test.selectAttendee();
                test.update(false);
                test.selectAttendee();
                test.delete(false);
                test.selectAttendee();
                test.selectAttendee();
                test.insert(true);
                test.selectAttendee();
                test.update(true);
                test.selectAttendee();
                test.delete(true);
                test.selectAttendee();
                test.close();
                break;
            case 2:
                test.rowSetSelect();
                test.rowSetInsert();
                test.rowSetSelect();
                test.rowSetUpdate();
                test.rowSetSelect();
                test.rowSetDelete();
                test.rowSetSelect();
                break;
            default:
                System.out.println("You selected nothing, too bad, we worked hard on this.");
                break;
        }
        
        
    }
    
}
