<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="/">
    	<xsl:apply-templates select="location"/>
  	</xsl:template>

	<xsl:template match="location">
    	<div style="padding-right: 8px; padding-left: 3px; margin-top: 2px">
    		<xsl:apply-templates select="info"/>
    	</div>
  	</xsl:template>

  	<xsl:template match="info">
    	
    	<xsl:variable name="page" select="../@arg0"/>

    	<div style="font-weight: bold; padding: 2px;">
    		Camera View:
    	</div>

    	<div style="font-weight: bold; padding: 2px;">
    		<xsl:value-of select="address"/>
    	</div>

   		<div style="padding: 2px;">			
			<img border="0" height="240" width="252" align="center" name="camimage">
	  			<xsl:attribute name="src">
	  				http://web007.dc.gov/camera/<xsl:value-of select="camid"/>/live.jpg?0.2
	  			</xsl:attribute>
				<xsl:attribute name="onload">
					javascript:loadImage('http://web007.dc.gov/camera/<xsl:value-of select="camid"/>/live.jpg?0.2');
				</xsl:attribute>
			</img>
   						
   		</div>   
		
	</xsl:template>

</xsl:stylesheet>