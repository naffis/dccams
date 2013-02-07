<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <xsl:apply-templates select="location"/>
  </xsl:template>

  <xsl:template match="location">
    <table>
      <tr>
        <xsl:if test="icon/@class != 'noicon'">
          <td style="vertical-align: top; padding-right: 4px;">
            <a>
	      <xsl:attribute name="href">javascript:showLocationInfo('<xsl:value-of select="@id"/>')</xsl:attribute>
              <img style="width:24px; height:38px" alt="">
                <xsl:attribute name="src">http://www.naffis.com/eventmapper/images/icon.png</xsl:attribute>
              </img>
            </a>
          </td>
        </xsl:if>
        <td style="padding-bottom: 0.5em; padding-top: 1px">
          <xsl:apply-templates select="info"/>
        </td>
      </tr>
    </table>
  </xsl:template>

  <xsl:template match="info">
    <div>
      <xsl:apply-templates select="address/line"/>
    </div>
  </xsl:template>

  <xsl:template match="line">
    <div><xsl:value-of select="."/></div>
  </xsl:template>

</xsl:stylesheet>
