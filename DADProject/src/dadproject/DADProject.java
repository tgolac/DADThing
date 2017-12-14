/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dadproject;
import com.sun.rowset.CachedRowSetImpl;
import com.sun.rowset.JdbcRowSetImpl;
import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.sql.RowSet;
import javax.sql.rowset.CachedRowSet;
import javax.sql.rowset.JdbcRowSet;
import javax.sql.rowset.RowSetFactory;
import javax.sql.rowset.RowSetProvider;

public class DADProject {
    
  private Connection con;
  private String  user, pass, dbname;
  private JdbcRowSet rowset;
  private CachedRowSet cRowset;
  
  
  public DADProject(String user, String pass, String dbname)
  {
      this.user = user;
      this.pass = pass;
      this.dbname = dbname;
  }
  
  public void rowSetInsert()
  {
      try 
      {
          RowSetFactory rsf = RowSetProvider.newFactory();
          Class.forName("com.mysql.jdbc.Driver");
          rowset = RowSetProvider.newFactory().createJdbcRowSet();
          rowset.setUrl("jdbc:mysql://localhost/" + dbname);
          rowset.setUsername(user);
          rowset.setPassword(pass);
          String st = "Select * from campus";
          //String st = "Select campus_name from campus where campus_shortname like ?";
          rowset.setCommand(st);

          rowset.execute();
          rowset.moveToInsertRow();
          rowset.updateInt("campus_id", 4);
          rowset.updateString("campus_name", "Rijeka");
          rowset.updateString("campus_shortName", "RI");
          rowset.insertRow();
          
         
          rowset.moveToInsertRow();
          rowset.updateInt("campus_id", 5);
          rowset.updateString("campus_name", "Split");
          rowset.updateString("campus_shortName", "ST");
          rowset.insertRow();
          
      } 
      catch (SQLException ex) 
      {
        System.out.println("An ");
        Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      } catch (ClassNotFoundException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      } 
  }
  
  public void rowSetDelete()
  {
    try
    {
        RowSetFactory rsf = RowSetProvider.newFactory();
        Class.forName("com.mysql.jdbc.Driver");
        rowset = RowSetProvider.newFactory().createJdbcRowSet();
        rowset.setUrl("jdbc:mysql://localhost/" + dbname);
        rowset.setUsername(user);
        rowset.setPassword(pass);
        String st = "Select * from campus";
        
        rowset.setCommand(st);
        rowset.execute();
        //deleting Zadar, formerly known as Rijeka
        rowset.absolute(4);
        
        rowset.deleteRow();
        
        //will delete Split, just in case
        rowset.last();
        rowset.deleteRow();
        
    } catch (SQLException ex) {
        System.out.println("Error, an SQLException has occurred.");
        Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      } catch (ClassNotFoundException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
    
    
  }
  
  public void rowSetUpdate()
  {
    try{    
        
        RowSetFactory rsf = RowSetProvider.newFactory();
        Class.forName("com.mysql.jdbc.Driver");
        rowset = RowSetProvider.newFactory().createJdbcRowSet();
        rowset.setUrl("jdbc:mysql://localhost/" + dbname);
        rowset.setUsername(user);
        rowset.setPassword(pass);
        String st = "Select * from campus";
        
        rowset.setCommand(st);

        rowset.execute();   
        
        //we will replace Rijeka with Zadar
        rowset.absolute(4);
        
        rowset.updateString("campus_name", "Zadar");
        rowset.updateString("campus_shortname", "ZD");
        
        rowset.updateRow();
        
    } catch (SQLException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      } catch (ClassNotFoundException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
  }
  
    public void rowSetSelect()
    {
        try
        {
            RowSetFactory rsf = RowSetProvider.newFactory();
            Class.forName("com.mysql.jdbc.Driver");
            rowset = RowSetProvider.newFactory().createJdbcRowSet();
            rowset.setUrl("jdbc:mysql://localhost/" + dbname);
            rowset.setUsername(user);
            rowset.setPassword(pass);
            String st = "Select campus_name from campus";
            //String st = "Select campus_name from campus where campus_shortname like ?";
            rowset.setCommand(st);
            
            
            rowset.execute();
            
            rowset.beforeFirst();
            System.out.println("Row Set results\n--------------");
            while(rowset.next())
            {
                System.out.println(rowset.getString(1));
            }
            
            System.out.println("\n");
            
            
        } 
        
        catch (SQLException ex) 
        {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
        } 
        
        catch (ClassNotFoundException ex)
        {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
        }
        
     
    }
  
  
  public void connect() throws ClassNotFoundException
  {
      try
      {
          Class.forName("com.mysql.jdbc.Driver");  
          con=DriverManager.getConnection("jdbc:mysql://localhost:3306/" + dbname, user, pass);
          System.out.println("You have connected");
      }
      
      catch(SQLException sqle)
      {
          System.out.println("Failed to connect");
      }
      
  }
  
  public void psSelect()
  {
     String st1 = "Select campus_name from campus where campus_shortname like ?";
     String st2 = "Select day_name from day where day_id like ?";
     String st3 = "Select room_name from room where campus_id like ?";
     
      try 
      {
          PreparedStatement ps1 = con.prepareStatement(st1);
          PreparedStatement ps2 = con.prepareStatement(st2);
          //scroll sensitive
          PreparedStatement ps3 = con.prepareStatement(st3, ResultSet.TYPE_SCROLL_INSENSITIVE);
          
          ps1.setString(1, "ZG");
          ps2.setInt(1, 6);
          ps3.setInt(1, 2);
          
          ResultSet rs1 = ps1.executeQuery();
          
          
          while(rs1.next())
          {
              for(int i=1; i <= rs1.getMetaData().getColumnCount(); i++)
              {
                  if( i>1)
                      System.out.println(", ");
                  System.out.print(rs1.getMetaData().getColumnName(i) + "\n" + rs1.getString(i));
              }
              System.out.println("\n\n");
          }
          
          ResultSet rs2 = ps2.executeQuery();
          
          while(rs2.next())
          {
              for(int i=1; i <= rs2.getMetaData().getColumnCount(); i++)
              {
                  if( i>1)
                      System.out.println(", ");
                  System.out.print(rs2.getMetaData().getColumnName(i) + "\n" + rs2.getString(i));
              }
              System.out.println("\n\n");
          }
          
          ResultSet rs3 = ps3.executeQuery();
          
          System.out.println("Default" + "\n--------");
          while(rs3.next())
          {
              for(int i=1; i <= rs3.getMetaData().getColumnCount(); i++)
              {
                  if( i>1)
                      System.out.println(", ");
                  System.out.print(rs3.getMetaData().getColumnName(i) + "\n" + rs3.getString(i));
              }
          }
          
          System.out.println("\n\nFrom Last to first");
          rs3.afterLast();
          System.out.println(rs3.getMetaData().getColumnName(1) + "\n--------");
          while(rs3.previous())
          {
              for(int i = rs3.getMetaData().getColumnCount(); i>=1; i--)
              {
                  System.out.println(rs3.getString(i));
              }
          }
          
        
          
          ps1.close();
          ps2.close();
          ps3.close();
          rs1.close();
          rs2.close();
          rs3.close();
      } 
      
      catch (SQLException ex) 
      {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
        
     
     
  }
  
  public void psSelect2()
  {
      System.out.println("\n");
      String st = "Select campus_name, room_id, room_videolink, room_name from campus join room using(campus_id)";
      try
      {  
        PreparedStatement ps = con.prepareStatement(st);
        
        ResultSet rs = ps.executeQuery();
        
        while(rs.next())
        {
            String cName = rs.getString(1);
            int rId = rs.getInt(2);
            boolean boolVLink;
            boolVLink = rs.getInt(3) == 1;
            String rName = rs.getString(4);
            
            System.out.println(rName + ", id " + rId + " of the " + cName + " campus, has video link: " + boolVLink);
            
        }
        System.out.println();
        rs.close();
        ps.close();
      } 
      catch 
      (SQLException ex) 
      {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
  }
  
  public void selectAttendee()
  {
      try
      {
          PreparedStatement ps = con.prepareStatement("select attendee_firstname, attendee_lastname from attendee");
          ResultSet rs = ps.executeQuery();
          
          while(rs.next())
          {
              System.out.println(rs.getString(1) + " " + rs.getString(2));
          }
          rs.close();
          ps.close();
      } 
      catch (SQLException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
  }
  
  public void insert(boolean isUpdateable)
  {
      System.out.println("\n");
      String st = "INSERT INTO attendee(attendee_id, attendee_firstname, attendee_lastname, attendee_type) values (?, ?, ?, ?)";
      try
      {
          
          PreparedStatement ps ;
          if(isUpdateable)
            ps = con.prepareStatement(st, ResultSet.CONCUR_UPDATABLE);
          else
            ps = con.prepareStatement(st);
          
          ps.setInt(1, 1);
          ps.setString(2, "Johhny");
          ps.setString(3, "Bravo");
          ps.setInt(4, 1);
          ps.execute();
          
          System.out.println("Values Inserted");
          
          ps.close();
      }
      
      catch (SQLException ex) 
      {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
  }
  
  public void update(boolean isUpdateable)
  {
      System.out.println("\n");
      String st = "UPDATE attendee set attendee_lastname = ? where attendee_id=?";
      try{
          
        PreparedStatement ps ;
        if(isUpdateable)
          ps = con.prepareStatement(st, ResultSet.CONCUR_UPDATABLE);
        else
          ps = con.prepareStatement(st);
        
        ps.setString(1, "Cash");
        ps.setInt(2, 1);
        ps.execute();
        System.out.println("Values Updated");
        ps.close();
      
      } catch (SQLException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
      
  }
  
  public void delete(boolean isUpdateable)
  {
      System.out.println("\n");
      String st = "Delete from attendee where attendee_id like ?";
      try
      {
        PreparedStatement ps ;
        if(isUpdateable)
          ps = con.prepareStatement(st, ResultSet.CONCUR_UPDATABLE);
        else
          ps = con.prepareStatement(st);
        
        ps.setInt(1, 1);
        ps.execute();
        System.out.println("Values Deleted");
        ps.close();
        
      } catch (SQLException ex) {
          Logger.getLogger(DADProject.class.getName()).log(Level.SEVERE, null, ex);
      }
  }
  
  public void close()
  {
      try
      {
          con.close();
          System.out.println("You have Closed the connection.");
      }
      
      catch(SQLException sqle)
      {
          System.out.println("Failed to close");
      }
  }
}
