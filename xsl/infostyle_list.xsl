<?xml version="1.0"?>

<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" />

	<xsl:template match="/">
		<table style="border:0">	
			<thead>
			<tr>
				<td>
					<a style="color:black; text-decoration:none;">
					   	<xsl:attribute name="href">
					   		javascript:void(sortby('address'))
					   	</xsl:attribute>
					   Address
					</a>								
				</td>
			</tr>
			</thead>
			<tbody>
			<xsl:for-each select="cams/cam">			
				<tr>					
					<td nowrap="nowrap">
						<a style="background:white; color:#3300FF; text-decoration:underline;">
						   	<xsl:attribute name="href">javascript:void(0)</xsl:attribute>
							<xsl:attribute name="onclick">
								javascript:showLoc('<xsl:value-of select="id"/>');
							</xsl:attribute>
						   <xsl:value-of select="address"/>
						</a>
					</td>
				</tr>
			</xsl:for-each>
			</tbody>
		</table>
	</xsl:template>
	
</xsl:stylesheet>